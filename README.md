<div align="center">

# 📊 Customer Retention System
### AI-Driven Churn Prediction & Revenue Analytics Platform

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![R](https://img.shields.io/badge/R-276DC3?style=for-the-badge&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/hanamotuma/Customer-Retention-System)

**A full-stack analytics platform that bridges web engineering and data science to predict customer churn and optimize retention strategies.**

</div>

---

## 📖 Overview

The **Customer Retention System** is a polyglot application designed for e-commerce businesses. It ingests raw customer transaction data, processes it through an embedded R Machine Learning engine using the RFM (Recency, Frequency, Monetary) model, and visualizes the churn risk in a dynamic Laravel-powered dashboard.

By identifying "High Risk" customers before they leave, businesses can take proactive steps (like offering discounts or re-engagement emails) to save revenue.

---

## 🧠 System Architecture & Logic

This project demonstrates how to connect a modern PHP web framework with a statistical programming language:

1. **The Web Layer (Laravel):** Manages the SQLite database and serves the UI.
2. **The Bridge (CSV Export):** When the dashboard loads, Laravel dynamically exports the database records into a temporary `customers.csv` file.
3. **The Brain (R Engine):** Laravel executes an `Rscript` command, passing the CSV file to a custom R algorithm.
4. **The Output (JSON & UI):** R calculates a Churn Probability Score (0-100%) and outputs a `churn_results.json` file. Laravel reads this JSON and renders a color-coded risk dashboard.

### 🔬 The Algorithm
The R engine categorizes customers using a weighted formula:
*   **Recency (50% weight):** Days since their last purchase (higher is worse).
*   **Frequency (25% weight):** Total number of purchases (lower is worse).
*   **Monetary (25% weight):** Total amount spent (lower is worse).

---

## 🚀 Quick Start Guide

Follow these steps to spin up the application locally. 

### Prerequisites
Ensure you have the following installed on your machine:
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="20" height="20" /> **PHP** (Version 8.1 or higher)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/composer/composer-original.svg" width="20" height="20" /> **Composer** (PHP dependency manager)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg" width="20" height="20" /> **R** (Statistical programming language, added to your system's PATH)
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg" width="20" height="20" /> **SQLite** (Laravel uses this by default, no server setup required)

### 1. Installation
Clone the repository and install the PHP dependencies:
```bash
git clone https://github.com/hanamotuma/Customer-Retention-System.git
cd Customer-Retention-System/customer-retention-app
composer install
