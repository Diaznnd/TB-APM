"""
app/services/retrain.py
Service untuk melatih ulang model dari data transaksi JSON
yang dikirim oleh Laravel (data kasir dari database).

Skema data yang diterima (list of dict):
[
    {
        "transaction_id": 1,
        "item": "Bread",
        "date_time": "30-10-2016 09:58",
        "period_day": "morning",
        "weekday_weekend": "weekend"
    },
    ...
]
"""

import os
import pandas as pd
import numpy as np
import joblib
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score

# Path ke folder models (relatif dari root proyek)
BASE_DIR    = os.path.join(os.path.dirname(__file__), '..', '..', 'models')
MODEL_PATH  = os.path.join(BASE_DIR, 'model.pkl')
METRICS_PATH = os.path.join(BASE_DIR, 'metrics.pkl')
STATE_PATH  = os.path.join(BASE_DIR, 'last_state.pkl')

# Untuk model per-produk
PRODUCT_MODELS_DIR = os.path.join(BASE_DIR, 'products')

FEATURES = ['lag_1', 'lag_2', 'day_of_week', 'month', 'ma_3']
TARGET   = 'jumlah_transaksi'


def _build_daily_features(daily: pd.DataFrame) -> pd.DataFrame:
    """
    Tambahkan feature engineering (lag, moving average, time features)
    ke DataFrame agregasi harian dengan re-indexing harian penuh.
    Mengisi tanggal kosong dengan 0 transaksi agar lag dihitung secara akurat.
    """
    daily = daily.copy()
    daily['date'] = pd.to_datetime(daily['date'])
    daily = daily.sort_values('date').set_index('date')
    
    # Buat rentang tanggal kalender penuh tanpa celah
    full_range = pd.date_range(start=daily.index.min(), end=daily.index.max(), freq='D')
    
    # Re-index dan isi hari kosong transaksi dengan 0
    daily = daily.reindex(full_range, fill_value=0).reset_index().rename(columns={'index': 'date'})
    
    # Buat fitur numerik runtun waktu
    daily['day_of_week'] = daily['date'].dt.dayofweek
    daily['month']       = daily['date'].dt.month
    
    # Hitung lag dan moving average secara konsisten
    daily['lag_1']       = daily[TARGET].shift(1)
    daily['lag_2']       = daily[TARGET].shift(2)
    daily['ma_3']        = daily[TARGET].rolling(3).mean()
    
    return daily.dropna().reset_index(drop=True)


def _train_rf(X_train, y_train):
    """Latih RandomForestRegressor dan kembalikan model."""
    rf = RandomForestRegressor(n_estimators=100, random_state=42)
    rf.fit(X_train, y_train)
    return rf


def _evaluate(model, X_test, y_test) -> dict:
    """Hitung MAE, RMSE, R2."""
    y_pred = model.predict(X_test)
    mae  = float(mean_absolute_error(y_test, y_pred))
    rmse = float(np.sqrt(mean_squared_error(y_test, y_pred)))
    r2   = float(r2_score(y_test, y_pred))
    return {
        "mae":  round(mae,  4),
        "rmse": round(rmse, 4),
        "r2":   round(r2,   4),
    }


def retrain_from_json(transactions: list) -> dict:
    """
    Latih ulang model dari data transaksi JSON (dari Laravel DB).

    Parameter:
        transactions – list of dict dengan field:
            transaction_id, item, date_time, period_day, weekday_weekend
            (dan opsional: quantity, harga_satuan, subtotal, kasir, metode_bayar)

    Kembalikan:
        dict dengan 'status', 'total_model', 'metrics_total', 'metrics_per_produk'
    """
    if not transactions or len(transactions) < 10:
        raise ValueError(
            "Data transaksi tidak cukup untuk retrain. "
            f"Minimal 10 baris, diterima: {len(transactions) if transactions else 0}."
        )

    df = pd.DataFrame(transactions)

    # --- Validasi kolom wajib ---
    required = ['item', 'date_time']
    missing = [c for c in required if c not in df.columns]
    if missing:
        raise ValueError(f"Kolom wajib tidak ada dalam data: {missing}")

    # --- Parse tanggal ---
    df['date_time'] = pd.to_datetime(df['date_time'], format="%d-%m-%Y %H:%M", errors='coerce')
    df = df.dropna(subset=['date_time'])
    df['date'] = df['date_time'].dt.date

    os.makedirs(BASE_DIR, exist_ok=True)
    os.makedirs(PRODUCT_MODELS_DIR, exist_ok=True)

    result = {"status": "ok", "total_model": {}, "per_produk": {}}

    # ================================================================
    # A. MODEL TOTAL (semua produk, jumlah transaksi per hari)
    # ================================================================
    daily_total = df.groupby('date').size().reset_index(name=TARGET)
    daily_total = _build_daily_features(daily_total)

    if len(daily_total) >= 5:
        X = daily_total[FEATURES]
        y = daily_total[TARGET]
        split = max(1, int(len(X) * 0.2))
        X_train, X_test = X.iloc[:-split], X.iloc[-split:]
        y_train, y_test = y.iloc[:-split], y.iloc[-split:]

        model_total = _train_rf(X_train, y_train)
        metrics_total = _evaluate(model_total, X_test, y_test) if len(X_test) > 0 else {}

        joblib.dump(model_total, MODEL_PATH)
        joblib.dump(metrics_total, METRICS_PATH)

        # Simpan last_state dari 5 hari terakhir
        daily_total_state = daily_total.copy()
        daily_total_state['date'] = daily_total_state['date'].dt.date
        last_state = daily_total_state[['date', TARGET]].tail(5).to_dict(orient='records')
        joblib.dump(last_state, STATE_PATH)

        result["total_model"] = {
            "trained": True,
            "total_hari": len(daily_total),
            "metrics": metrics_total,
        }
    else:
        result["total_model"] = {
            "trained": False,
            "alasan": f"Data harian total tidak cukup: {len(daily_total)} hari (minimal 5)",
        }

    # ================================================================
    # B. MODEL PER-PRODUK (Top 20 produk saja agar stabil)
    # ================================================================
    top_products = df['item'].value_counts().head(20).index.tolist()
    per_produk_summary = {}

    for product in top_products:
        df_prod = df[df['item'] == product].copy()
        daily_prod = df_prod.groupby('date').size().reset_index(name=TARGET)
        daily_prod = _build_daily_features(daily_prod)

        if len(daily_prod) < 5:
            per_produk_summary[product] = {
                "trained": False,
                "alasan": f"Data tidak cukup: {len(daily_prod)} hari",
            }
            continue

        X = daily_prod[FEATURES]
        y = daily_prod[TARGET]
        split = max(1, int(len(X) * 0.2))
        X_train, X_test = X.iloc[:-split], X.iloc[-split:]
        y_train, y_test = y.iloc[:-split], y.iloc[-split:]

        model_prod = _train_rf(X_train, y_train)
        metrics_prod = _evaluate(model_prod, X_test, y_test) if len(X_test) > 0 else {}

        # Simpan model per produk: models/products/<item_slug>.pkl
        slug = product.lower().replace(' ', '_').replace('/', '_')
        prod_model_path = os.path.join(PRODUCT_MODELS_DIR, f"{slug}.pkl")
        prod_state_path = os.path.join(PRODUCT_MODELS_DIR, f"{slug}_state.pkl")
        joblib.dump(model_prod, prod_model_path)
        daily_prod_state = daily_prod.copy()
        daily_prod_state['date'] = daily_prod_state['date'].dt.date
        last_state_prod = daily_prod_state[['date', TARGET]].tail(5).to_dict(orient='records')
        joblib.dump(last_state_prod, prod_state_path)

        per_produk_summary[product] = {
            "trained": True,
            "total_hari": len(daily_prod),
            "metrics": metrics_prod,
        }

    result["per_produk"] = per_produk_summary
    result["total_produk_dilatih"] = sum(
        1 for v in per_produk_summary.values() if v.get("trained")
    )

    # Reload model ke memory (update global state di app/model.py)
    try:
        from app.model import load_model
        load_model()
    except Exception:
        pass  # Jika gagal reload, tidak apa-apa – model sudah disimpan di disk

    return result
