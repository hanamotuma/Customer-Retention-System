<div align="center">

# 📊 Customer Retention System
### AI-Driven Churn Prediction & Revenue Analytics Platform

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)](https://vuejs.org/)
[![R](https://img.shields.io/badge/R-276DC3?style=for-the-badge&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)

**A polyglot, full-stack analytics platform that bridges modern web engineering (Laravel & Vue) with advanced data science (R Machine Learning) to predict customer churn and optimize retention strategies.**

</div>

---

## 📖 Overview

The **Customer Retention System** is an enterprise-grade application designed for e-commerce businesses. It ingests raw customer transaction data, processes it through an embedded R Machine Learning engine using a **Random Forest Classifier**, and visualizes the churn risk in a dynamic, Vue.js-powered dashboard.

By identifying "High Risk" customers before they leave, businesses can take proactive steps (like offering discounts or re-engagement emails) to save revenue and optimize customer lifetime value.

---

## 🧠 System Architecture & Logic

This project demonstrates a true polyglot architecture, utilizing the best language for each specific task:

1. **The Frontend (Vue.js + Vite):** A reactive, single-page application featuring a modern Bento-box dashboard, Chart.js visualizations, and dynamic data binding.
2. **The Manager (Laravel/PHP):** Manages the SQLite database, handles routing, and acts as the bridge between the UI and the data engine.
3. **The Bridge (CSV Export):** When the dashboard loads, Laravel dynamically exports database records into a temporary `customers.csv` file.
4. **The Brain (R Machine Learning):** Laravel executes an `Rscript` command, passing the CSV file to a custom R algorithm. The R script trains a Random Forest model, predicts probabilities, and outputs a JSON file.
5. **The Output (JSON & UI):** Laravel parses the JSON predictions and passes them to Vue, which renders a color-coded risk dashboard.

### 🔬 The Machine Learning Algorithm (Random Forest)
Unlike simple weighted formulas, this system uses an actual Machine Learning model:
*   **Data Split:** The R engine splits the data into a 70% Training set and a 30% Testing set.
*   **Training:** It builds 100 Decision Trees (`ntree = 100`) using Recency, Frequency, and Monetary data as predictors.
*   **Prediction:** It calculates the exact probability of churn (0-100%) for every single customer in the database.

---

## 🚀 Quick Start Guide

Follow these steps to spin up the application locally. 

### Prerequisites
Ensure you have the following installed on your machine:
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="20" height="20" /> **PHP** (Version 8.1 or higher)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/composer/composer-original.svg" width="20" height="20" /> **Composer** (PHP dependency manager)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg" width="20" height="20" /> **R** (Statistical programming language)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" width="20" height="20" /> **Node.js & NPM** (For Vite/Vue)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg" width="20" height="20" /> **SQLite** (No setup required)

### 1. Clone the Repository
```bash
git clone https://github.com/hanamotuma/Customer-Retention-System.git
cd Customer-Retention-System/customer-retention-app
