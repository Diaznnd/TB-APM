"""
train_model.py
Script untuk melatih model RandomForest dari dataset bakery
Sesuai dengan notebook tesAPM.ipynb
"""

import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import joblib
import os

# =========================================================
# 1. DATA COLLECTION
# =========================================================
print("=== Memuat dataset ===")
df = pd.read_csv("data/dataset.csv")
print(f"Total baris: {len(df)}")
print(df.head())

# =========================================================
# 2. DATA PREPROCESSING
# =========================================================
print("\n=== Preprocessing ===")

# Hapus nilai kosong
df = df.dropna()
print(f"Total baris setelah dropna: {len(df)}")

# Transformasi datetime
df['date_time'] = pd.to_datetime(df['date_time'], format="%d-%m-%Y %H:%M")

# Pisahkan tanggal
df['date'] = df['date_time'].dt.date

# Agregasi harian: hitung jumlah transaksi per hari
daily = df.groupby('date').size().reset_index(name='jumlah_transaksi')
daily['date'] = pd.to_datetime(daily['date'])
print(f"Total hari unik: {len(daily)}")
print(daily.head())

# =========================================================
# 3. FEATURE ENGINEERING (sesuai notebook)
# =========================================================
print("\n=== Feature Engineering ===")

daily['day_of_week'] = daily['date'].dt.dayofweek   # 0=Senin ... 6=Minggu
daily['month']       = daily['date'].dt.month

daily['lag_1'] = daily['jumlah_transaksi'].shift(1)          # H-1
daily['lag_2'] = daily['jumlah_transaksi'].shift(2)          # H-2
daily['ma_3']  = daily['jumlah_transaksi'].rolling(3).mean() # MA 3 hari

# Hapus baris dengan NaN yang dihasilkan oleh shift/rolling
daily = daily.dropna()
print(f"Total hari setelah dropna lag/ma: {len(daily)}")
print(daily.head())

# =========================================================
# 4. SPLIT DATA
# =========================================================
FEATURES = ['lag_1', 'lag_2', 'day_of_week', 'month', 'ma_3']
TARGET   = 'jumlah_transaksi'

X = daily[FEATURES]
y = daily[TARGET]

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, shuffle=False
)
print(f"\nTrain size: {len(X_train)}, Test size: {len(X_test)}")

# =========================================================
# 5. PELATIHAN MODEL
# =========================================================
print("\n=== Melatih model RandomForestRegressor ===")
model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X_train, y_train)
print("Model selesai dilatih!")

# =========================================================
# 6. EVALUASI MODEL
# =========================================================
y_pred = model.predict(X_test)

mae  = mean_absolute_error(y_test, y_pred)
rmse = np.sqrt(mean_squared_error(y_test, y_pred))
r2   = r2_score(y_test, y_pred)

print(f"\n=== Evaluasi Model ===")
print(f"MAE  : {mae:.4f}")
print(f"RMSE : {rmse:.4f}")
print(f"R2   : {r2:.4f}")

# Interpretasi otomatis (sesuai notebook)
print("\nInterpretasi Model:")
print(f"- MAE : {'Sangat baik' if mae < 10 else 'Baik' if mae < 20 else 'Kurang baik'}")
print(f"- RMSE: {'Sangat baik' if rmse < 15 else 'Baik' if rmse < 25 else 'Kurang baik'}")
print(f"- R2  : {'Sangat baik' if r2 > 0.8 else 'Baik' if r2 > 0.6 else 'Cukup' if r2 > 0.4 else 'Kurang baik'}")

# =========================================================
# 7. SIMPAN MODEL & METADATA
# =========================================================
os.makedirs("models", exist_ok=True)

# Simpan model
joblib.dump(model, "models/model.pkl")
print("\nModel disimpan ke models/model.pkl")

# Simpan metadata evaluasi
metrics = {
    "mae":  round(mae, 4),
    "rmse": round(rmse, 4),
    "r2":   round(r2, 4),
}
joblib.dump(metrics, "models/metrics.pkl")
print("Metrics disimpan ke models/metrics.pkl")

# Simpan nilai akhir dari daily agar bisa dipakai untuk prediksi tanpa harus
# memuat ulang seluruh CSV (ambil 2 baris terakhir sebagai "state" lag)
last_state = daily[['date', 'jumlah_transaksi']].tail(5).to_dict(orient='records')
joblib.dump(last_state, "models/last_state.pkl")
print("Last state disimpan ke models/last_state.pkl")

print("\nSelesai! Jalankan 'python run.py' untuk memulai API.")