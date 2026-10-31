<template>
    <div class="app-container" :class="{ 'dark-mode': isDark }">
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <img :src="'/images/logo.png'" alt="Logo" class="logo-img" />
            </div>
            <nav class="nav-menu">
                <a href="#" class="nav-item active"><i class="fa-solid fa-house"></i></a>
                <a href="#" class="nav-item"><i class="fa-solid fa-chart-pie"></i></a>
                <a href="#" class="nav-item"><i class="fa-solid fa-users"></i></a>
                <a href="#" class="nav-item"><i class="fa-solid fa-gear"></i></a>
            </nav>
            <div class="nav-bottom">
                <a href="#" class="nav-item" @click.prevent="toggleTheme">
                    <i :class="isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
                </a>
                <a href="#" class="nav-item text-red" @click.prevent="logout" style="margin-top: 10px;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            
            <!-- Header with Download Dropdown -->
            <header class="top-header">
                <div>
                    <p class="greeting">Dashboard Overview</p>
                    <h1>Customer Information</h1>
                </div>
                
                <div class="header-actions">
                    <!-- Single Download Button -->
                    <button @click="showExportMenu = !showExportMenu" class="btn-outline" style="padding: 8px 12px;">
                        <i class="fa-solid fa-download"></i> Export
                    </button>

                    <!-- Dropdown Menu -->
                    <div v-if="showExportMenu" class="export-dropdown">
                        <button @click="exportData('excel'); showExportMenu = false" class="dropdown-item">
                            <i class="fa-solid fa-file-excel text-green"></i> Export to Excel
                        </button>
                        <button @click="exportData('pdf'); showExportMenu = false" class="dropdown-item">
                            <i class="fa-solid fa-file-pdf text-red"></i> Export to PDF
                        </button>
                    </div>

                    <div class="user-profile" style="margin-left: 15px;">
                        <img src="https://ui-avatars.com/api/?name=Jane+Doe&background=random" class="avatar" />
                    </div>
                </div>
            </header>

            <!-- Loading State -->
            <div v-if="loading" class="loading-screen">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Initializing AI Engine...
            </div>

            <div v-else>
                <!-- KPIs -->
                <div class="kpi-row">
                    <div class="kpi-box">
                        <div class="kpi-icon bg-blue"><i class="fa-solid fa-users"></i></div>
                        <div>
                            <p class="kpi-title">Total Customers</p>
                            <h3 class="kpi-val">{{ customers.length.toLocaleString() }}</h3>
                        </div>
                    </div>
                    <div class="kpi-box">
                        <div class="kpi-icon bg-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div>
                            <p class="kpi-title">High Risk</p>
                            <h3 class="kpi-val text-red">{{ highRiskCount }}</h3>
                        </div>
                    </div>
                    <div class="kpi-box">
                        <div class="kpi-icon bg-green"><i class="fa-solid fa-shield-halved"></i></div>
                        <div>
                            <p class="kpi-title">Safe Customers</p>
                            <h3 class="kpi-val text-green">{{ lowRiskCount }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="dashboard-grid">
                    
                    <!-- Left Column -->
                    <div class="left-col">
                        <div class="section-header">
                            <h2>Risk Distribution Matrix</h2>
                        </div>
                        
                        <!-- Bento Boxes -->
                        <div class="bento-grid">
                            <div class="bento-card bg-gradient-blue">
                                <div class="bento-top">
                                    <span>High Risk</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </div>
                                <h2 class="bento-val">{{ highRiskCount }}</h2>
                                <p class="bento-desc">Requires immediate intervention</p>
                            </div>
                            <div class="bento-card bg-gradient-teal">
                                <div class="bento-top">
                                    <span>Moderate Risk</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </div>
                                <h2 class="bento-val">{{ moderateRiskCount }}</h2>
                                <p class="bento-desc">Needs re-engagement</p>
                            </div>
                            <div class="bento-card bg-dark">
                                <div class="bento-top">
                                    <span>Low Risk</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </div>
                                <h2 class="bento-val">{{ lowRiskCount }}</h2>
                                <p class="bento-desc">Loyal and healthy</p>
                            </div>
                        </div>

                        <!-- Data Table (Must be here) -->
                        <div class="table-section">
                            <CustomerTable :customers="customers" />
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="right-col">
                        <div class="profile-card">
                            <div class="profile-info">
                                <div class="profile-avatar-wrap">
                                    <div class="profile-avatar">AI</div>
                                    <div class="status-dot"></div>
                                </div>
                                <h2>R Engine</h2>
                                <p class="role">Machine Learning Model</p>
                            </div>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fa-solid fa-microchip"></i>
                                    <span>Random Forest / RFM</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fa-solid fa-check-circle text-green"></i>
                                    <span>Active & Listening</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import CustomerTable from './CustomerTable.vue';

const customers = ref([]);
const loading = ref(true);
const isDark = ref(false);
const showExportMenu = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) document.body.classList.add('dark-theme');
    else document.body.classList.remove('dark-theme');
};

const highRiskCount = computed(() => customers.value.filter(c => c.risk_label === 'High Risk').length);
const moderateRiskCount = computed(() => customers.value.filter(c => c.risk_label === 'Moderate Risk').length);
const lowRiskCount = computed(() => customers.value.filter(c => c.risk_label === 'Low Risk').length);

const fetchData = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/churn-data', {
            headers: { Authorization: `Bearer ${token}` }
        });
        customers.value = response.data;
    } catch (err) {
        console.error("API Error:", err);
    } finally {
        loading.value = false;
    }
};

const exportData = async (type) => {
    try {
        const token = localStorage.getItem('token');
        const endpoint = type === 'excel' ? '/api/export/excel' : '/api/export/pdf';
        
        const response = await axios.get(endpoint, {
            headers: { Authorization: `Bearer ${token}` },
            responseType: 'blob' 
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', type === 'excel' ? 'Retention_Data.xlsx' : 'Risk_Report.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (err) {
        console.error("Export failed", err);
        alert(`Failed to export ${type.toUpperCase()}. Check terminal.`);
    }
};

const logout = () => {
    localStorage.removeItem('token');
    window.location.reload(); 
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

:root {
    --bg-main: #f4f6f9;
    --bg-card: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
    --sidebar-bg: #0f172a;
}

:global(body.dark-theme) {
    --bg-main: #0f172a;
    --bg-card: #1e293b;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --border-color: #334155;
}

.app-container { display: flex; height: 100vh; font-family: 'Inter', sans-serif; background: var(--bg-main); color: var(--text-main); transition: background 0.3s; }

/* SIDEBAR */
.sidebar { width: 80px; background: var(--sidebar-bg); display: flex; flex-direction: column; align-items: center; padding: 30px 0; }
.logo { margin-bottom: 40px; }
.logo-img { width: 40px; height: 40px; border-radius: 8px; object-fit: contain; }
.nav-menu, .nav-bottom { display: flex; flex-direction: column; gap: 15px; }
.nav-item { color: #64748b; font-size: 1.2rem; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; text-decoration: none; transition: 0.2s; }
.nav-item:hover, .nav-item.active { background: #1e293b; color: white; }
.nav-bottom { margin-top: auto; }
.text-red { color: #ef4444; }

/* MAIN CONTENT */
.main-content { flex: 1; overflow-y: auto; padding: 30px 40px; }
.top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.greeting { font-size: 0.85rem; color: var(--text-muted); margin: 0 0 5px 0; }
.top-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
.header-actions { display: flex; align-items: center; gap: 10px; position: relative; }
.btn-outline { padding: 8px 16px; border: 1px solid var(--border-color); background: transparent; color: var(--text-main); border-radius: 6px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; }
.btn-outline:hover { background: var(--bg-card); }
.avatar { width: 40px; height: 40px; border-radius: 50%; }

/* DROPDOWN */
.export-dropdown {
    position: absolute;
    top: 50px;
    right: 60px; 
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    width: 180px;
    z-index: 9999; 
    overflow: hidden;
}
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 12px 15px;
    background: transparent;
    border: none;
    text-align: left;
    font-size: 0.9rem;
    color: var(--text-main);
    cursor: pointer;
    transition: background 0.2s;
}
.dropdown-item:hover { background: var(--bg-main); }

/* KPIs */
.kpi-row { display: flex; gap: 20px; margin-bottom: 30px; }
.kpi-box { background: var(--bg-card); padding: 20px 24px; border-radius: 12px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 20px; flex: 1; }
.kpi-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; }
.bg-blue { background: #e0f2fe; color: #0284c7; }
.bg-red { background: #fee2e2; color: #dc2626; }
.bg-green { background: #dcfce7; color: #16a34a; }
.kpi-title { font-size: 0.85rem; color: var(--text-muted); margin: 0 0 5px 0; font-weight: 500;}
.kpi-val { font-size: 1.5rem; font-weight: 700; margin: 0; }
.text-red { color: #ef4444; }
.text-green { color: #10b981; }

/* GRID */
.dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
.left-col { display: flex; flex-direction: column; gap: 20px; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.section-header h2 { font-size: 1.1rem; font-weight: 600; margin: 0; }

/* BENTO */
.bento-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;}
.bento-card { padding: 25px; border-radius: 16px; color: white; }
.bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); grid-column: span 2; }
.bg-gradient-teal { background: linear-gradient(135deg, #14b8a6, #0f766e); }
.bg-dark { background: #1e293b; }
.bento-top { display: flex; justify-content: space-between; font-size: 0.85rem; opacity: 0.9; margin-bottom: 20px; }
.bento-val { font-size: 2rem; font-weight: 700; margin: 0 0 10px 0; }
.bento-desc { font-size: 0.85rem; opacity: 0.8; margin: 0; }

/* PROFILE */
.profile-card { background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-color); padding: 24px; text-align: center; }
.profile-avatar-wrap { position: relative; display: inline-block; margin-bottom: 15px; }
.profile-avatar { width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; font-weight: 700; color: #0f172a; border: 4px solid var(--bg-card); }
.status-dot { position: absolute; bottom: 5px; right: 5px; width: 16px; height: 16px; background: #10b981; border-radius: 50%; border: 2px solid var(--bg-card); }
.profile-info h2 { margin: 0 0 5px 0; font-size: 1.2rem; }
.role { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 25px; }
.contact-info { display: flex; flex-direction: column; gap: 15px; text-align: left; background: var(--bg-main); padding: 15px; border-radius: 12px; }
.contact-item { display: flex; align-items: center; gap: 12px; font-size: 0.85rem; color: var(--text-main); font-weight: 500;}
.contact-item i { color: var(--text-muted); }

/* LOADING */
.loading-screen { display: flex; justify-content: center; align-items: center; height: 100%; font-size: 1.2rem; color: var(--text-muted); gap: 10px; }

@media (max-width: 1024px) {
    .dashboard-grid { grid-template-columns: 1fr; }
    .kpi-row { flex-direction: column; }
}
</style>