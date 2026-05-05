"""
app/model.py
Memuat model RandomForest yang sudah dilatih dari models/model.pkl
"""

import os
import joblib

# Path ke file model relatif terhadap root proyek
MODEL_PATH   = os.path.join(os.path.dirname(__file__), '..', 'models', 'model.pkl')
METRICS_PATH = os.path.join(os.path.dirname(__file__), '..', 'models', 'metrics.pkl')
STATE_PATH   = os.path.join(os.path.dirname(__file__), '..', 'models', 'last_state.pkl')

model = None
metrics = None
last_state = None

def load_model():
    """Muat model dan metadata dari disk."""
    global model, metrics, last_state

    if not os.path.exists(MODEL_PATH):
        raise FileNotFoundError(
            f"Model belum dilatih. Jalankan 'python train_model.py' terlebih dahulu.\n"
            f"Path: {os.path.abspath(MODEL_PATH)}"
        )

    model      = joblib.load(MODEL_PATH)
    metrics    = joblib.load(METRICS_PATH) if os.path.exists(METRICS_PATH) else {}
    last_state = joblib.load(STATE_PATH)   if os.path.exists(STATE_PATH)   else []

    print(f"[INFO] Model dimuat dari {MODEL_PATH}")
    return model
