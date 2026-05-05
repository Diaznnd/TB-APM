"""
app/routes.py
API Endpoints Flask untuk sistem prediksi bakery.

Endpoint:
  GET  /health               – Cek apakah API berjalan
  GET  /metrics              – Metrik evaluasi model (MAE, RMSE, R²)
  POST /predict/manual       – Prediksi dengan input manual
  POST /predict/auto         – Prediksi otomatis berdasarkan tanggal
  GET  /predict/weekly       – Prediksi 7 hari ke depan (total transaksi)
  GET  /predict/weekly/produk– Prediksi 7 hari ke depan per-produk
  POST /retrain              – Retrain model dari data JSON (dari Laravel DB)
"""

from flask import Blueprint, request, jsonify
from app.services.predict import (
    predict_manual, predict_auto, get_metrics,
    predict_weekly, predict_weekly_by_product,
)
from app.services.retrain import retrain_from_json

main = Blueprint('main', __name__)


# ------------------------------------------------------------------
# GET /health
# ------------------------------------------------------------------
@main.route('/health', methods=['GET'])
def health():
    """Cek status API."""
    return jsonify({"status": "ok", "pesan": "Flask API berjalan!"})


# ------------------------------------------------------------------
# GET /metrics
# ------------------------------------------------------------------
@main.route('/metrics', methods=['GET'])
def model_metrics():
    """
    Kembalikan metrik evaluasi model:
      - MAE  (Mean Absolute Error)
      - RMSE (Root Mean Squared Error)
      - R²   (Koefisien Determinasi)
    """
    try:
        hasil = get_metrics()
        return jsonify(hasil)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ------------------------------------------------------------------
# POST /predict/manual
# ------------------------------------------------------------------
@main.route('/predict/manual', methods=['POST'])
def predict_manual_api():
    """
    Prediksi jumlah transaksi dengan input manual.

    Body JSON (semua wajib):
    {
        "lag_1":       <float> – jumlah transaksi kemarin,
        "lag_2":       <float> – jumlah transaksi 2 hari lalu,
        "day_of_week": <int>   – hari (0=Senin, 6=Minggu),
        "month":       <int>   – bulan (1-12),
        "ma_3":        <float> – rata-rata 3 hari terakhir
    }
    """
    data = request.get_json(silent=True)
    if not data:
        return jsonify({"error": "Request body harus berupa JSON."}), 400

    required_fields = ['lag_1', 'lag_2', 'day_of_week', 'month', 'ma_3']
    missing = [f for f in required_fields if f not in data]
    if missing:
        return jsonify({"error": f"Field wajib tidak ada: {missing}"}), 400

    try:
        hasil = predict_manual(
            lag_1       = float(data['lag_1']),
            lag_2       = float(data['lag_2']),
            day_of_week = int(data['day_of_week']),
            month       = int(data['month']),
            ma_3        = float(data['ma_3']),
        )
        return jsonify(hasil)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ------------------------------------------------------------------
# POST /predict/auto
# ------------------------------------------------------------------
@main.route('/predict/auto', methods=['POST'])
def predict_auto_api():
    """
    Prediksi otomatis berdasarkan tanggal target.
    Lag dan moving-average dihitung dari data historis terakhir
    yang tersimpan saat melatih model.

    Body JSON:
    {
        "tanggal": "YYYY-MM-DD"
    }
    """
    data = request.get_json(silent=True)
    if not data or 'tanggal' not in data:
        return jsonify({"error": "Field 'tanggal' wajib ada (format: YYYY-MM-DD)."}), 400

    try:
        hasil = predict_auto(tanggal=data['tanggal'])
        return jsonify(hasil)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ------------------------------------------------------------------
# GET /predict/weekly
# ------------------------------------------------------------------
@main.route('/predict/weekly', methods=['GET'])
def predict_weekly_api():
    """
    Prediksi rolling 7 hari ke depan untuk TOTAL transaksi harian.
    Menggunakan data historis dari last_state.pkl.

    Query param (opsional):
        days=7   – jumlah hari yang ingin diprediksi (default 7, max 30)

    Response:
    {
        "mode": "total",
        "mulai": "YYYY-MM-DD",
        "hari": 7,
        "prediksi": [
            {"tanggal": "YYYY-MM-DD", "prediksi": 120, "fitur": {...}},
            ...
        ]
    }
    """
    try:
        days = int(request.args.get('days', 7))
        days = max(1, min(days, 30))  # clamp antara 1–30
        hasil = predict_weekly(days=days)
        return jsonify(hasil)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ------------------------------------------------------------------
# GET /predict/weekly/produk
# ------------------------------------------------------------------
@main.route('/predict/weekly/produk', methods=['GET'])
def predict_weekly_produk_api():
    """
    Prediksi rolling 7 hari ke depan untuk setiap PRODUK secara terpisah.
    Hanya produk yang memiliki model tersimpan di models/products/ yang akan muncul.

    Query param (opsional):
        days=7   – jumlah hari yang ingin diprediksi (default 7, max 30)

    Response:
    {
        "mode": "per_produk",
        "mulai": "YYYY-MM-DD",
        "hari": 7,
        "total_produk": 20,
        "produk": {
            "Bread": [{"tanggal": ..., "prediksi": ..., "fitur": {...}}, ...],
            "Coffee": [...],
            ...
        }
    }
    """
    try:
        days = int(request.args.get('days', 7))
        days = max(1, min(days, 30))
        hasil = predict_weekly_by_product(days=days)
        return jsonify(hasil)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ------------------------------------------------------------------
# POST /retrain
# ------------------------------------------------------------------
@main.route('/retrain', methods=['POST'])
def retrain_api():
    """
    Latih ulang model dari data transaksi JSON yang dikirim Laravel.
    Ini dipanggil otomatis oleh Laravel Scheduler jam 02:00.

    Body JSON:
    {
        "transactions": [
            {
                "transaction_id": 1,
                "item": "Bread",
                "date_time": "30-10-2016 09:58",
                "period_day": "morning",
                "weekday_weekend": "weekend"
            },
            ...
        ]
    }

    Response:
    {
        "status": "ok",
        "total_model": {"trained": true, "total_hari": 90, "metrics": {...}},
        "per_produk": {"Bread": {"trained": true, ...}, ...},
        "total_produk_dilatih": 18
    }
    """
    data = request.get_json(silent=True)
    if not data or 'transactions' not in data:
        return jsonify({"error": "Field 'transactions' (array) wajib ada."}), 400

    transactions = data['transactions']
    if not isinstance(transactions, list):
        return jsonify({"error": "'transactions' harus berupa array/list."}), 400

    try:
        # Log jumlah data yang diterima
        with open("flask_debug.log", "a") as f:
            f.write(f"Received {len(transactions)} transactions for retraining.\n")
            
        hasil = retrain_from_json(transactions)
        return jsonify(hasil)
    except ValueError as e:
        return jsonify({"error": str(e)}), 422
    except Exception as e:
        import traceback
        with open("flask_error.log", "a") as f:
            f.write(traceback.format_exc())
        return jsonify({"error": str(e)}), 500