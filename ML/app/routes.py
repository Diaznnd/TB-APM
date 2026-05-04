@main.route("/forecast", methods=["POST"])
def forecast():
    data = request.json
    
    # contoh input: [150, 130, 120]
    result = predict_result(data["lags"])

    return jsonify({
        "forecast": result
    })