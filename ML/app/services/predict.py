import numpy as np
from app.model import model

def forecast(lags):
    data = np.array(lags).reshape(1, -1)
    result = model.predict(data)
    return float(result[0])