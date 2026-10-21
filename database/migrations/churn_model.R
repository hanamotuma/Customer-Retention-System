library(jsonlite)

args <- commandArgs(trailingOnly = TRUE)
input_file <- args[1]

data <- read.csv(input_file)

data$churn_probability <- (
  (data$recency / 365) * 0.5 +       
  (1 - (data$frequency / 50)) * 0.25 + 
  (1 - (data$monetary / 5000)) * 0.25  
) * 100

data$churn_probability <- pmax(0, pmin(100, data$churn_probability))

data$risk_label <- ifelse(data$churn_probability > 70, "High Risk",
                   ifelse(data$churn_probability > 40, "Moderate Risk", "Low Risk"))

json_output <- toJSON(data, pretty = TRUE)
write(json_output, "churn_results.json")