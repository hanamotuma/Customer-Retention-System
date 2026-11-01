# plumber.R
library(plumber)
library(jsonlite)

#* @apiTitle Churn Prediction API
#* @apiDescription Receives customer data and returns churn predictions.

#* Predict Churn
#* @param data The customer data as JSON
#* @post /predict
function(req, res) {
  # 1. Parse incoming JSON from Laravel
  json_data <- fromJSON(req$postBody)
  df <- as.data.frame(json_data)
  
  # 2. Simulate the AI Model (Replace with your Random Forest logic if you want)
  df$churn_probability <- (df$recency / 365) * 100
  df$risk_label <- ifelse(df$churn_probability > 70, "High Risk", 
                   ifelse(df$churn_probability > 40, "Moderate Risk", "Low Risk"))
  
  # 3. Return the JSON response
  return(df)
}