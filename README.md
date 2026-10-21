<div align="center">

# Customer Retention System
**AI-Driven Churn Prediction & Revenue Analytics Platform**

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![R](https://img.shields.io/badge/R-276DC3?style=flat-square&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)

</div>

---

## Overview

The **Customer Retention System** is a full-stack analytics platform built to identify churn risk before customers leave. It exports customer transaction data from Laravel into an embedded **R Machine Learning engine**, computes churn probability using the **RFM (Recency, Frequency, Monetary)** model, and delivers dynamic risk scores directly to a web dashboard.

---

## Key Features

* **Predictive Dashboard:** Visualizes customer accounts into High, Medium, and Low risk tiers.
* **Polyglot Pipeline:** Automated background communication between PHP and R scripts via JSON outputs.
* **RFM Scoring Algorithm:** Weighted evaluation based on **Recency (50%)**, **Frequency (25%)**, and **Monetary Value (25%)**.
* **Zero-Config Database:** Lightweight setup powered by SQLite.

---

## Quick Start

### Prerequisites
* PHP `>= 8.1` | Composer | R (with `Rscript` added to system PATH)

### Setup

```bash
# Clone repository
git clone [https://github.com/hanamotuma/Customer-Retention-System.git](https://github.com/hanamotuma/Customer-Retention-System.git)
cd Customer-Retention-System/customer-retention-app

# Install dependencies & set up environment
composer install
cp .env.example .env
php artisan key:generate

# Initialize SQLite database & seed sample data
touch database/database.sqlite
php artisan migrate --seed

# Start server
php artisan serve
