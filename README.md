<div align="center">

# 📊 Customer Retention System
### AI-Driven Churn Prediction & Revenue Analytics Platform

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![R](https://img.shields.io/badge/R-276DC3?style=for-the-badge&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/hanamotuma/Customer-Retention-System)

**A polyglot full-stack analytics platform bridging web engineering and data science to predict customer churn and safeguard recurring revenue.**

[View Architecture](#-system-architecture--logic) • [Features](#-key-features) • [Quick Start](#-quick-start-guide) • [Algorithm](#-the-churn-algorithm)

</div>

---

## 📖 Overview

The **Customer Retention System** is a full-stack analytics application designed for e-commerce and subscription businesses. It ingests raw transaction data, passes it through an embedded **R Machine Learning engine** using the **RFM (Recency, Frequency, Monetary)** model, and presents dynamic risk scores through a clean Laravel dashboard.

By identifying high-risk customers before they churn, businesses can take targeted re-engagement actions—such as automated email offers, loyalty incentives, or custom support outreach—to maximize customer lifetime value.

---

## ✨ Key Features

* 📈 **Dynamic Churn Dashboard:** Real-time visibility into customer risk profiles categorized into High, Medium, and Low Risk.
* 🔄 **Seamless Polyglot Integration:** Automated background data exchange between PHP (Laravel) and R scripts via JSON output pipelines.
* 🎯 **RFM Scoring Engine:** Weighted statistical algorithm evaluating customer transaction recency, order frequency, and monetary spending.
* ⚡ **Zero-Config Local Database:** Fast and lightweight storage utilizing SQLite.
* 🎨 **Responsive Dashboard UI:** Clean interface built for fast data scannability and quick decision-making.

---

## 🧠 System Architecture & Logic
