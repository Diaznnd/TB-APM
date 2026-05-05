"""
app/services/predict.py
Service layer untuk logika prediksi/forecasting.
Fitur sesuai notebook tesAPM.ipynb:
  lag_1       – jumlah transaksi H-1
  lag_2       – jumlah transaksi H-2
  day_of_week – hari dalam seminggu (0=Senin … 6=Minggu)
  month       – bulan (1-12)
  ma_3        – rata-rata bergerak 3 hari
"""

import os
import joblib
import pandas as pd
import numpy as np
from app.model import model, metrics, last_state

FEATURES = ['lag_1', 'lag_2', 'day_of_week', 'month', 'ma_3']

# Path ke model per-produk
BASE_DIR           = os.path.join(os.path.dirname(__file__), '..', '..', 'models')
PRODUCT_MODELS_DIR = os.path.join(BASE_DIR, 'products')


# ------------------------------------------------------------------
# Helper Internal
# ------------------------------------------------------------------

def _predict_one(rf_model, lag_1: float, lag_2: float,
                 day_of_week: int, month: int, ma_3: float) -> int:
    """Prediksi satu hari menggunakan model yang diberikan."""
    df = pd.DataFrame([{
        'lag_1': lag_1, 'lag_2': lag_2,
        'day_of_week': day_of_week, 'month': month, 'ma_3': ma_3,
    }])
    raw = rf_model.predict(df[FEATURES])[0]
    return max(0, round(raw))


def _rolling_predict(rf_model, history: list, start_date: pd.Timestamp,
                     days: int = 7) -> list:
    """
    Prediksi rolling `days` hari ke depan.
    history – list jumlah transaksi harian (minimal 3 elemen), urut lama ke baru.
    Mengembalikan list of dict: [{'tanggal': str, 'prediksi': int, 'fitur': dict}]
    """
    hist = list(history)  # copy
    results = []

    for i in range(days):
        target_dt   = start_date + pd.Timedelta(days=i)
        lag_1       = float(hist[-1])
        lag_2       = float(hist[-2])
        ma_3        = float(np.mean(hist[-3:]))
        day_of_week = target_dt.dayofweek
        month       = target_dt.month

        pred = _predict_one(rf_model, lag_1, lag_2, day_of_week, month, ma_3)

        results.append({
            "tanggal":  target_dt.strftime('%Y-%m-%d'),
            "prediksi": pred,
            "fitur": {
                "lag_1":       lag_1,
                "lag_2":       lag_2,
                "day_of_week": day_of_week,
                "month":       month,
                "ma_3":        round(ma_3, 2),
            }
        })
        # Tambahkan prediksi ke history untuk iterasi berikutnya
        hist.append(pred)

    return results


# ------------------------------------------------------------------
# Public API
# ------------------------------------------------------------------

def predict_manual(lag_1: float, lag_2: float, day_of_week: int,
                   month: int, ma_3: float) -> dict:
    """
    Prediksi satu hari ke depan dengan input manual.
    Semua parameter wajib diisi oleh pemanggil API.
    """
    input_df = pd.DataFrame([{
        'lag_1':       lag_1,
        'lag_2':       lag_2,
        'day_of_week': day_of_week,
        'month':       month,
        'ma_3':        ma_3,
    }])

    raw = model.predict(input_df[FEATURES])[0]
    result = max(0, round(raw))

    return {
        "prediksi_transaksi": result,
        "input": {
            "lag_1":       lag_1,
            "lag_2":       lag_2,
            "day_of_week": day_of_week,
            "month":       month,
            "ma_3":        ma_3,
        }
    }


def predict_auto(tanggal: str) -> dict:
    """
    Prediksi otomatis untuk tanggal tertentu berdasarkan
    data historis yang tersimpan di models/last_state.pkl.

    Parameter:
        tanggal – string tanggal format 'YYYY-MM-DD'
    """
    if not last_state or len(last_state) < 3:
        raise ValueError(
            "Data historis tidak cukup. Pastikan train_model.py sudah dijalankan "
            "dengan dataset yang memiliki minimal 3 hari."
        )

    # Ambil jumlah transaksi dari 2 hari terakhir yang diketahui
    history = [row['jumlah_transaksi'] for row in last_state]
    lag_1 = history[-1]
    lag_2 = history[-2]
    ma_3  = float(np.mean(history[-3:]))

    target_dt  = pd.to_datetime(tanggal)
    day_of_week = target_dt.dayofweek
    month       = target_dt.month

    input_df = pd.DataFrame([{
        'lag_1':       lag_1,
        'lag_2':       lag_2,
        'day_of_week': day_of_week,
        'month':       month,
        'ma_3':        ma_3,
    }])

    raw    = model.predict(input_df[FEATURES])[0]
    result = max(0, round(raw))

    return {
        "tanggal":            tanggal,
        "prediksi_transaksi": result,
        "fitur_otomatis": {
            "lag_1":       lag_1,
            "lag_2":       lag_2,
            "day_of_week": day_of_week,
            "month":       month,
            "ma_3":        round(ma_3, 2),
        }
    }


def predict_weekly(days: int = 7) -> dict:
    """
    Prediksi rolling `days` hari ke depan (default 7 hari / 1 minggu).
    Menggunakan last_state dari model total.

    Mengembalikan:
    {
        "mode": "total",
        "mulai": "YYYY-MM-DD",
        "prediksi": [ {"tanggal": ..., "prediksi": ..., "fitur": {...}}, ... ]
    }
    """
    if not last_state or len(last_state) < 3:
        raise ValueError(
            "Data historis tidak cukup. Pastikan model sudah dilatih "
            "dengan minimal 3 hari data."
        )

    history   = [row['jumlah_transaksi'] for row in last_state]
    # Hari pertama prediksi = hari setelah tanggal terakhir di last_state
    last_date = pd.to_datetime(str(last_state[-1]['date']))
    start     = last_date + pd.Timedelta(days=1)

    predictions = _rolling_predict(model, history, start, days)

    return {
        "mode":     "total",
        "mulai":    start.strftime('%Y-%m-%d'),
        "hari":     days,
        "prediksi": predictions,
    }


def predict_weekly_by_product(days: int = 7) -> dict:
    """
    Prediksi rolling `days` hari ke depan untuk setiap produk
    yang memiliki model tersimpan di models/products/.
    """
    product_results = {}
    
    if not os.path.isdir(PRODUCT_MODELS_DIR):
        return {
            "mode":         "per_produk",
            "mulai":        None,
            "hari":         days,
            "total_produk": 0,
            "produk":       {},
            "pesan":        "Folder models/products/ belum ada. Jalankan retrain untuk melatih model per-produk."
        }

    # Gunakan last_state total sebagai acuan tanggal mulai
    if not last_state or len(last_state) < 1:
        raise ValueError("last_state kosong. Pastikan model total sudah dilatih.")

    last_date = pd.to_datetime(str(last_state[-1]['date']))
    start     = last_date + pd.Timedelta(days=1)

    product_results = {}

    # Cari semua model produk (.pkl, kecuali yang _state.pkl)
    for fname in os.listdir(PRODUCT_MODELS_DIR):
        if not fname.endswith('.pkl') or fname.endswith('_state.pkl'):
            continue

        slug           = fname.replace('.pkl', '')
        prod_model_path = os.path.join(PRODUCT_MODELS_DIR, fname)
        prod_state_path = os.path.join(PRODUCT_MODELS_DIR, f"{slug}_state.pkl")

        if not os.path.exists(prod_state_path):
            continue

        try:
            prod_model = joblib.load(prod_model_path)
            prod_state = joblib.load(prod_state_path)

            if len(prod_state) < 3:
                continue

            history = [row['jumlah_transaksi'] for row in prod_state]
            preds   = _rolling_predict(prod_model, history, start, days)

            # Kembalikan nama produk asli (slug -> nama) dari file state
            product_name = slug.replace('_', ' ').title()
            product_results[product_name] = preds

        except Exception:
            continue

    return {
        "mode":         "per_produk",
        "mulai":        start.strftime('%Y-%m-%d'),
        "hari":         days,
        "total_produk": len(product_results),
        "produk":       product_results,
    }


def get_metrics() -> dict:
    """Kembalikan metrik evaluasi model (MAE, RMSE, R²)."""
    if not metrics:
        return {"pesan": "Metrik tidak tersedia. Jalankan train_model.py terlebih dahulu."}

    mae  = metrics.get("mae",  None)
    rmse = metrics.get("rmse", None)
    r2   = metrics.get("r2",   None)

    def interpret_mae(v):
        if v is None: return "-"
        return "Sangat baik" if v < 10 else "Baik" if v < 20 else "Kurang baik"

    def interpret_rmse(v):
        if v is None: return "-"
        return "Sangat baik" if v < 15 else "Baik" if v < 25 else "Kurang baik"

    def interpret_r2(v):
        if v is None: return "-"
        return "Sangat baik" if v > 0.8 else "Baik" if v > 0.6 else "Cukup" if v > 0.4 else "Kurang baik"

    return {
        "mae":  {"nilai": mae,  "interpretasi": interpret_mae(mae)},
        "rmse": {"nilai": rmse, "interpretasi": interpret_rmse(rmse)},
        "r2":   {"nilai": r2,   "interpretasi": interpret_r2(r2)},
    }