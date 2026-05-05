"""
app/__init__.py
Flask application factory.
Model dimuat saat aplikasi pertama kali dibuat.
"""

from flask import Flask
from app.model import load_model


def create_app():
    app = Flask(__name__)

    # Muat model RandomForest sebelum request pertama
    with app.app_context():
        load_model()

    # Daftarkan blueprint routes
    from app.routes import main
    app.register_blueprint(main)

    return app