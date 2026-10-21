# Customer Retention System
**AI-Driven Churn Risk Analysis & Revenue Analytics**

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/R-276DC3?style=for-the-badge&logo=r&logoColor=white" />
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" />
</p>

## Overview

A full-stack analytics platform that uses **RFM (Recency, Frequency, Monetary)** scoring to assess customer churn risk and visualize customer insights through a Laravel dashboard.

## Features

- Customer risk classification: **High, Medium, Low**
- Weighted RFM scoring and revenue analysis
- Laravel–R integration through JSON
- Interactive customer analytics dashboard

## Scoring Algorithm

```r
churn_score <- (recency_score * 0.50) +
               (frequency_score * 0.25) +
               (monetary_score * 0.25)
```

## Installation

```bash
git clone https://github.com/hanamotuma/Customer-Retention-System.git
cd Customer-Retention-System/customer-retention-app

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**Requirements:** PHP · Composer · R · SQLite

---

<p align="center">
  <b>Built by Hana Motuma</b><br/>
  <a href="https://github.com/hanamotuma">GitHub Profile</a>
</p>
