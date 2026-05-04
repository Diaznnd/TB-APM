import pandas as pd
from sklearn.ensemble import RandomForestRegressor
import joblib

# === LOAD DATA (ganti dengan dataset kamu) ===
df = pd.read_csv("data.csv")

# misal kolom target: 'value'
df["lag1"] = df["value"].shift(1)
df["lag2"] = df["value"].shift(2)
df["lag3"] = df["value"].shift(3)

df = df.dropna()

X = df[["lag1", "lag2", "lag3"]]
y = df["value"]

# === MODEL ===
model = RandomForestRegressor(n_estimators=100)
model.fit(X, y)

# === SIMPAN ===
joblib.dump(model, "models/model.pkl")

print("Model forecasting siap!")