<template>
    <!-- The :class binding toggles dark mode dynamically -->
    <div class="app-container" :class="{ 'dark-mode': isDarkMode }">
        
        <!-- 1. SIDEBAR -->
        <div class="sidebar">
            <!-- Logo Integration -->
            <div class="sidebar-logo">
                <img src="/images/logo.png" alt="Retention Logo" class="logo-img" v-if="!isDarkMode">
                <!-- If you have a white version of the logo for dark mode, you could swap it here. 
                     For now, we will just use the same image. -->
                <img src="/images/logo.png" alt="Retention Logo" class="logo-img" v-if="isDarkMode">
            </div>
            
            <div class="nav-icon active"><i class="fa-solid fa-house"></i></div>
            <div class="nav-icon"><i class="fa-solid fa-users"></i></div>
            <div class="nav-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="nav-icon"><i class="fa-solid fa-gear"></i></div>
        </div>

        <!-- 2. MAIN DASHBOARD -->
        <div class="main-dashboard">
            <div class="header">
                <div>
                    <p class="subtitle">Dashboard</p>
                    <h1>Customer Retention System</h1>
                </div>
                
                <div class="header-actions">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleTheme" class="theme-toggle">
                        <i v-if="isDarkMode" class="fa-solid fa-sun"></i>
                        <i v-else class="fa-solid fa-moon"></i>
                    </button>
                    
                    <div class="search-bar">
                        <i class="fa-solid fa-search"></i> Search
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=16a085&color=fff" class="avatar">
                </div>
            </div>

            <div class="grid-layout">
                <!-- Large Brand Blue Card -->
                <div class="card card-primary">
                    <div style="display: flex; justify-content: space-between;">
                        <h2>Total Managed Customers</h2>
                        <span class="badge-live">Live Data</span>
                    </div>
                    <div>
                        <div class="big-number">{{ customers.length }}</div>
                        <p style="opacity: 0.8; font-size: 0.9rem;">AI processed successfully</p>
                    </div>
                </div>

                <!-- Teal AI Engine Card -->
                <div class="card card-secondary">
                    <i class="fa-solid fa-microchip" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.8;"></i>
                    <h2 style="font-size: 1.5rem; margin-bottom: 5px;">R Engine</h2>
                    <p style="opacity: 0.9; font-size: 0.9rem;">Random Forest Active</p>
                </div>

                <!-- White/Gray KPI 1 -->
                <div class="card card-kpi">
                    <div>
                        <div class="icon-box icon-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="kpi-title">High Risk Customers</div>
                        <div class="kpi-value text-danger">{{ highRiskCount }}</div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill fill-danger" style="width: 45%;"></div></div>
                </div>

                <!-- White/Gray KPI 2 -->
                <div class="card card-kpi">
                    <div>
                        <div class="icon-box icon-success"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="kpi-title">Safe Customers</div>
                        <div class="kpi-value text-success">{{ lowRiskCount }}</div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill fill-success" style="width: 75%;"></div></div>
                </div>

                <!-- White/Gray KPI 3 -->
                <div class="card card-kpi">
                    <div>
                        <div class="icon-box icon-warning"><i class="fa-solid fa-chart-pie"></i></div>
                        <div class="kpi-title">Avg. Churn Prob.</div>
                        <div class="kpi-value text-warning">{{ avgChurn }}%</div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill fill-warning" style="width: 60%;"></div></div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <h2 class="table-title">Recent Customer Risk Profiles</h2>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Recency</th>
                                <th>Frequency</th>
                                <th>Monetary</th>
                                <th>Risk Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="customer in customers.slice(0, 50)" :key="customer.id">
                                <td><strong>{{ customer.name }}</strong></td>
                                <td>{{ customer.recency }} days</td>
                                <td>{{ customer.frequency }} orders</td>
                                <td>${{ parseFloat(customer.monetary).toFixed(2) }}</td>
                                <td>
                                    <span v-if="customer.risk_label === 'High Risk'" class="badge badge-high">High Risk</span>
                                    <span v-else-if="customer.risk_label === 'Moderate Risk'" class="badge badge-mod">Moderate</span>
                                    <span v-else class="badge badge-low">Low Risk</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. RIGHT PANEL -->
        <div class="right-panel">
            <h2>Risk Distribution <span class="view-all">View All</span></h2>
            <div class="chart-container">
                <canvas id="riskChart"></canvas>
            </div>
            <div style="margin-top: 40px;">
                <h2>R Engine Status</h2>
                <div class="status-box">
                    <div class="status-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <h4 class="status-title">Model Trained</h4>
                        <p class="status-sub">Random Forest Algorithm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    initialCustomers: { type: Array, default: () => [] }
});

const customers = ref(props.initialCustomers);
const isDarkMode = ref(false);
let chartInstance = null; // Keep track of the chart to update colors

const highRiskCount = computed(() => customers.value.filter(c => c.risk_label === 'High Risk').length);
const lowRiskCount = computed(() => customers.value.filter(c => c.risk_label === 'Low Risk').length);
const avgChurn = computed(() => {
    if (customers.value.length === 0) return 0;
    const total = customers.value.reduce((sum, c) => sum + parseFloat(c.churn_probability || 0), 0);
    return (total / customers.value.length).toFixed(1);
});

// Theme Toggle Logic
const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
};

// Function to render/update chart based on theme
const renderChart = () => {
    let high = 0, moderate = 0, low = 0;
    customers.value.forEach(c => {
        if (c.risk_label === 'High Risk') high++;
        else if (c.risk_label === 'Moderate Risk') moderate++;
        else low++;
    });

    const textColor = isDarkMode.value ? '#e2e8f0' : '#1e293b';
    
    if (chartInstance) {
        chartInstance.destroy(); // Destroy old chart before making a new one
    }

    const ctx = document.getElementById('riskChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['High', 'Moderate', 'Low'],
            datasets: [{
                data: [high, moderate, low],
                backgroundColor: ['#e74c3c', '#f39c12', '#16a085'], // Brand Colors
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        boxWidth: 8, 
                        color: textColor, // Update text color based on theme
                        font: {family: 'Poppins', size: 11} 
                    } 
                }
            }
        }
    });
};

onMounted(() => {
    renderChart();
});

// Watch for theme changes and re-render chart to fix text colors
watch(isDarkMode, () => {
    renderChart();
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* --- GLOBAL VARIABLES (Brand Colors from Logo) --- */
:root {
    --brand-blue: #1f618d;     /* Logo Dark Blue */
    --brand-teal: #16a085;     /* Logo Teal/Green */
    --brand-gray: #7f8c8d;     /* Logo Gray */
    
    --bg-body: #eef1f6;
    --bg-panel: #ffffff;
    --bg-card: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --border-color: #f1f5f9;
}

/* --- DARK MODE VARIABLES --- */
.dark-mode {
    --bg-body: #0f172a;        /* Dark Slate */
    --bg-panel: #1e293b;       /* Lighter Slate for panels */
    --bg-card: #334155;        /* Card background */
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --border-color: #334155;
}

* { box-sizing: border-box; margin: 0; padding: 0; transition: background-color 0.3s, color 0.3s; }
body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); color: var(--text-main); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

/* --- LAYOUT --- */
.app-container { display: flex; width: 1400px; height: 900px; background: var(--bg-panel); border-radius: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); overflow: hidden; border: 10px solid var(--border-color); }

/* --- SIDEBAR --- */
.sidebar { width: 100px; background: var(--bg-panel); display: flex; flex-direction: column; align-items: center; padding: 30px 0; border-right: 1px solid var(--border-color); }
.sidebar-logo { margin-bottom: 50px; display: flex; justify-content: center; width: 100%; }
.logo-img { width: 65px; height: auto; object-fit: contain; } /* Adjust based on your logo size */
.nav-icon { width: 50px; height: 50px; border-radius: 15px; display: flex; justify-content: center; align-items: center; margin-bottom: 20px; color: var(--text-muted); cursor: pointer; transition: all 0.3s; }
.nav-icon:hover, .nav-icon.active { background: var(--brand-blue); color: white; box-shadow: 0 10px 20px rgba(31, 97, 141, 0.3); }

/* --- MAIN DASHBOARD --- */
.main-dashboard { flex: 1; padding: 40px; overflow-y: auto; background: var(--bg-body); }
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header h1 { font-size: 1.8rem; color: var(--text-main); font-weight: 600; }
.subtitle { color: var(--text-muted); font-size: 0.9rem; }
.header-actions { display: flex; gap: 15px; align-items: center; }
.search-bar { background: var(--bg-card); padding: 10px 20px; border-radius: 20px; color: var(--text-muted); box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
.avatar { border-radius: 50%; width: 45px; }

/* Theme Toggle Button */
.theme-toggle { background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); width: 45px; height: 45px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; transition: 0.3s; }
.theme-toggle:hover { transform: scale(1.1); }

/* --- GRID LAYOUT --- */
.grid-layout { display: grid; grid-template-columns: 2fr 1fr; grid-template-rows: auto auto auto; gap: 25px; }
.card { border-radius: 30px; padding: 30px; position: relative; overflow: hidden; }

/* Primary Blue Card */
.card-primary { grid-column: 1 / 3; background: linear-gradient(135deg, var(--brand-blue), #154360); color: white; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; }
.card-primary h2 { font-size: 1.2rem; font-weight: 400; opacity: 0.9; margin-bottom: 10px; }
.card-primary .big-number { font-size: 3rem; font-weight: 700; }
.badge-live { background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; }

/* Secondary Teal Card */
.card-secondary { background: linear-gradient(135deg, var(--brand-teal), #117a65); color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; }

/* KPI Cards */
.card-kpi { background: var(--bg-card); color: var(--text-main); box-shadow: 0 10px 30px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between; border: 1px solid var(--border-color); }
.card-kpi .icon-box { width: 50px; height: 50px; border-radius: 15px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 20px; }
.icon-danger { background: #fee2e2; color: #e74c3c; }
.icon-success { background: #d1fae5; color: var(--brand-teal); }
.icon-warning { background: #fef3c7; color: #f39c12; }
.kpi-title { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px; }
.kpi-value { font-size: 1.8rem; font-weight: 700; color: var(--text-main); }
.text-danger { color: #e74c3c; }
.text-success { color: var(--brand-teal); }
.text-warning { color: #f39c12; }
.progress-bar { height: 6px; background: var(--border-color); border-radius: 3px; margin-top: 15px; overflow: hidden;}
.progress-fill { height: 100%; border-radius: 3px; }
.fill-danger { background: #e74c3c; }
.fill-success { background: var(--brand-teal); }
.fill-warning { background: #f39c12; }

/* --- RIGHT PANEL --- */
.right-panel { width: 320px; background: var(--bg-panel); padding: 40px 30px; border-left: 1px solid var(--border-color); }
.right-panel h2 { font-size: 1.1rem; color: var(--text-main); margin-bottom: 20px; display: flex; justify-content: space-between;}
.view-all { color: var(--brand-blue); font-size: 0.8rem; cursor: pointer; }
.chart-container { position: relative; width: 100%; height: 200px; margin-top: 20px;}
.status-box { background: var(--bg-body); padding: 15px; border-radius: 15px; margin-top: 15px; display: flex; align-items: center; gap: 15px; border: 1px solid var(--border-color); }
.status-icon { width: 40px; height: 40px; background: #d1fae5; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: var(--brand-teal); }
.status-title { font-size: 0.9rem; color: var(--text-main); }
.status-sub { font-size: 0.75rem; color: var(--text-muted); }

/* --- TABLE --- */
.table-container { background: var(--bg-card); border-radius: 20px; padding: 20px; margin-top: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid var(--border-color); }
.table-title { font-size: 1.1rem; margin-bottom: 15px; color: var(--text-main); }
table { width: 100%; border-collapse: collapse; text-align: left; }
th { padding: 15px; font-size: 0.8rem; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-transform: uppercase; }
td { padding: 15px; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-main); }
.badge { padding: 5px 10px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; }
.badge-high { background: #fee2e2; color: #e74c3c; }
.badge-mod { background: #fef3c7; color: #f39c12; }
.badge-low { background: #d1fae5; color: var(--brand-teal); }
</style>