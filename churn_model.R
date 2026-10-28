# Load necessary libraries
library(jsonlite)
library(randomForest)

# Read command line arguments
args <- commandArgs(trailingOnly = TRUE)
input_file <- args[1]

data <- read.csv(input_file)

# 1. Create a target label for training (Simulating historical churn data)
# We tell the AI: "If recency > 180, label them as churned (1)"
data$is_churned <- ifelse(data$recency > 180, 1, 0)

# 2. Train the Random Forest Model
set.seed(123)
rf_model <- randomForest(
  is_churned ~ recency + frequency + monetary, 
  data = data, 
  ntree = 100,
  nodesize = 5
)

# 3. Predict the probability of churn for all customers
# type = "prob" gives us percentages instead of just 1 or 0
data$churn_probability <- predict(rf_model, type = "prob")[,2] * 100

# 4. Assign risk labels based on AI predictions
data$risk_label <- ifelse(data$churn_probability > 70, "High Risk",
                   ifelse(data$churn_probability > 40, "Moderate Risk", "Low Risk"))

# 5. Remove the training column so it doesn't clutter the frontend
data$is_churned <- NULL

# 6. Output to JSON for Laravel
json_output <- toJSON(data, pretty = TRUE)
write(json_output, "churn_results.json")