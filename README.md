<div align="center">

# Customer Retention System
### AI-Driven Churn Prediction & Revenue Analytics

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D)](https://vuejs.org/)
[![R](https://img.shields.io/badge/R-276DC3?style=for-the-badge&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)

**A full-stack analytics platform bridging web engineering and data science to predict customer churn and optimize retention strategies.**

</div>

---

## 📖 Project Overview

The **Customer Retention System** is a polyglot application designed for e-commerce businesses. It ingests raw customer transaction data, processes it through an embedded R Machine Learning engine using the RFM (Recency, Frequency, Monetary) model, and visualizes the churn risk in a dynamic Vue.js frontend served by Laravel.

By identifying "High Risk" customers before they leave, businesses can take proactive steps to save revenue.

---

## 🛠️ Technology Stack

*   **Backend Framework:** Laravel (PHP)
*   **Statistical Engine:** R (Plumber API for real-time predictions)
*   **Frontend:** Vue.js 3 (Composition API), Chart.js
*   **Database:** SQLite (Zero-config, easily swappable to PostgreSQL)
*   **Styling:** Custom CSS (Glassmorphism & Dark Mode support)

---

## 🧠 System Architecture & Data Flow

1. **The Frontend (Vue.js):** User logs in and requests the dashboard data via an API.
2. **The Backend (Laravel):** Authenticates the user and queries the SQLite database.
3. **The Bridge (HTTP Request):** Laravel sends the raw customer data to the R Plumber API.
4. **The Brain (R Engine):** R runs the Random Forest/RFM algorithm, calculates the Churn Probability Score (0-100%), and returns JSON.
5. **The Presentation:** Laravel passes the JSON to Vue, which renders the Bento-box dashboard, Data Table, and Interactive Charts.

### 🔬 The Algorithm
The R engine categorizes customers using a weighted formula:
*   **Recency (50%):** Days since last purchase (Higher is worse).
*   **Frequency (25%):** Total purchases (Lower is worse).
*   **Monetary (25%):** Total spent (Lower is worse).

---

## ✨ Key Features

*   **AI-Powered Risk Scoring:** Integrates R Plumber API for live machine learning predictions.
*   **Interactive Dashboard:** Real-time KPI cards, Bento-box risk matrix, and Chart.js visualizations.
*   **Dark/Light Mode:** Full CSS variable theming for user preference.
*   **Data Export:** One-click export to Excel (.xlsx) and PDF reports.
*   **Advanced Data Table:** Search, sort, and paginate through 1,000+ customer records.
*   **Secure Authentication:** Token-based authentication using Laravel Sanctum.

---

