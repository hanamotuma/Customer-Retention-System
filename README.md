<div align="center">

# Customer Retention System
### AI-Driven Churn Prediction Platform

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=flat-square&logo=vuedotjs&logoColor=4FC08D)](https://vuejs.org/)
[![R](https://img.shields.io/badge/R-276DC3?style=flat-square&logo=r&logoColor=white)](https://www.r-project.org/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Vite](https://img.shields.io/badge/Vite-B73BFE?style=flat-square&logo=vite&logoColor=FFD62E)](https://vitejs.dev/)

**A full-stack analytics platform bridging web engineering**
**and data science to predict customer churn and optimize**
**retention strategies.**

</div>

---

## 📖 Project Overview

The **Customer Retention System** is a polyglot application 
designed for e-commerce businesses. It ingests raw customer 
transaction data, processes it through an embedded R Machine 
Learning engine using the RFM (Recency, Frequency, Monetary) 
model, and visualizes the churn risk in a dynamic Vue.js 
frontend served by Laravel.

By identifying "High Risk" customers before they leave, 
businesses can take proactive steps to save revenue.

---

## 🛠️ Technology Stack

### Backend & Architecture
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="16" height="16" /> **PHP / Laravel** - Core API and routing.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg" width="16" height="16" /> **R Programming** - Statistical analysis.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg" width="16" height="16" /> **SQLite** - Zero-config database.

### Frontend & UI
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg" width="16" height="16" /> **Vue.js 3** - Reactive frontend components.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vitejs/vitejs-original.svg" width="16" height="16" /> **Vite** - Next-gen frontend tooling.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" width="16" height="16" /> **HTML5 / CSS3** - Custom layouts.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" width="16" height="16" /> **JavaScript** - Chart.js integration.

### DevOps & Tools
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg" width="16" height="16" /> **Git** - Version control.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg" width="16" height="16" /> **GitHub** - Code hosting.
*   <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg" width="16" height="16" /> **VS Code** - IDE.

---

## 🧠 System Architecture

1. **The Web Layer (Laravel):** Manages the database and 
   serves the API.
2. **The Bridge (CSV Export):** Laravel exports database 
   records into a temporary CSV file.
3. **The Brain (R Engine):** Laravel executes an `Rscript` 
   command, passing the CSV to the R algorithm.
4. **The Output (JSON & UI):** R calculates a Churn Score 
   and outputs JSON. Vue.js renders the interactive chart.

### 🔬 The RFM Algorithm
The R engine categorizes customers using a weighted formula:
*   **Recency (50%):** Days since last purchase (Higher is worse).
*   **Frequency (25%):** Total purchases (Lower is worse).
*   **Monetary (25%):** Total spent (Lower is worse).

---

## 🛣️ Future Roadmap
- [x] Implement R Machine Learning integration.
- [x] Design custom modern UI.
- [x] Migrate from Blade to Vue.js 3.
- [ ] Implement User Authentication (Laravel Sanctum).
- [ ] Add a file upload UI for custom CSV datasets.
- [ ] Deploy to a cloud provider (AWS / DigitalOcean).

---
*Built with Laravel, Vue.js, R, and a focus on data.*
