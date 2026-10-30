<template>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">■ RetentionOS</div>
            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    <span class="icon">▦</span> Overview
                </a>
                <a href="#" class="nav-item">
                    <span class="icon">👥</span> Customers
                </a>
                <a href="#" class="nav-item">
                    <span class="icon">📈</span> Reports
                </a>
            </nav>
            <div class="sidebar-footer">
                <button @click="logout" class="btn-logout">Sign out</button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <h2>Customer Overview</h2>
                <div class="user-profile">
                    <div class="avatar">A</div>
                    <span>Admin User</span>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <div v-if="loading" class="loading-state">Fetching data from AI Engine...</div>

                <div v-else class="dashboard-grid">
                    <!-- KPI Cards -->
                    <div class="card">
                        <div class="card-title">Total Customers</div>
                        <div class="card-value">{{ customers.length.toLocaleString() }}</div>
                        <div class="card-desc">Active in database</div>
                    </div>

                    <div class="card">
                        <div class="card-title">High Risk</div>
                        <div class="card-value text-red">{{ highRiskCount.toLocaleString() }}</div>
                        <div class="card-desc">Require immediate action</div>
                    </div>

                    <div class="card">
                        <div class="card-title">Avg. Churn Probability</div>
                        <div class="card-value text-orange">
                            {{ averageChurn }}%
                        </div>
                        <div class="card-desc">Across all segments</div>
                    </div>
                </div>

                <!-- Data Table Area (Placeholder for the grid) -->
                <div v-if="!loading" class="card table-card">
                    <div class="card-header">
                        <h3>Recent Predictions</h3>
                        <button class="btn-secondary">Export CSV</button>
                    </div>
                    <div class="table-placeholder">
                        <p>Your 1,000 customer rows would be rendered here in a clean data table.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Auth from './Auth.vue';

const isAuthenticated = ref(!!localStorage.getItem('token'));
const customers = ref([]);
const loading = ref(true);

const highRiskCount = computed(() => {
    return customers.value.filter(c => c.risk_label === 'High Risk').length;
});

const averageChurn = computed(() => {
    if (!customers.value.length) return 0;
    const total = customers.value.reduce((sum, c) => sum + (c.churn_probability || 0), 0);
    return (total / customers.value.length).toFixed(1);
});

const fetchData = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/churn-data', {
            headers: { Authorization: `Bearer ${token}` }
        });
        customers.value = response.data;
    } catch (err) {
        if (err.response?.status === 401) logout();
    } finally {
        loading.value = false;
    }
};

const logout = async () => {
    try {
        const token = localStorage.getItem('token');
        await axios.post('/api/logout', {}, { headers: { Authorization: `Bearer ${token}` } });
    } catch (e) {} 
    finally {
        localStorage.removeItem('token');
        isAuthenticated.value = false;
    }
};

onMounted(() => {
    if (isAuthenticated.value) fetchData();
});
</script>

<style scoped>
.app-layout { display: flex; height: 100vh; font-family: 'Inter', system-ui, sans-serif; background: #f8fafc; }
.sidebar { width: 250px; background: #ffffff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; }
.logo { padding: 24px; font-size: 1.1rem; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0; }
.nav-menu { padding: 20px 12px; flex: 1; }
.nav-item { display: flex; align-items: center; padding: 10px 12px; color: #475569; text-decoration: none; border-radius: 6px; margin-bottom: 4px; font-size: 0.95rem; font-weight: 500; transition: background 0.2s; }
.nav-item:hover { background: #f1f5f9; }
.nav-item.active { background: #eff6ff; color: #2563eb; }
.icon { margin-right: 12px; font-size: 1.1rem; }
.sidebar-footer { padding: 20px; border-top: 1px solid #e2e8f0; }
.btn-logout { width: 100%; padding: 8px; background: white; border: 1px solid #cbd5e1; color: #64748b; border-radius: 6px; cursor: pointer; font-weight: 500; }
.btn-logout:hover { background: #f8fafc; color: #0f172a; }
.main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
.top-header { height: 70px; background: white; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; padding: 0 30px; }
.top-header h2 { font-size: 1.25rem; font-weight: 600; color: #0f172a; margin: 0; }
.user-profile { display: flex; align-items: center; gap: 10px; font-weight: 500; font-size: 0.9rem; color: #475569; }
.avatar { width: 32px; height: 32px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #0f172a; }
.content-area { padding: 30px; max-width: 1200px; }
.dashboard-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
.card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; }
.card-title { font-size: 0.875rem; color: #64748b; font-weight: 500; margin-bottom: 8px; }
.card-value { font-size: 2rem; font-weight: 700; color: #0f172a; }
.card-desc { font-size: 0.8rem; color: #94a3b8; margin-top: 8px; }
.text-red { color: #dc2626; }
.text-orange { color: #d97706; }
.table-card { padding: 0; overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.card-header h3 { margin: 0; font-size: 1rem; font-weight: 600; color: #0f172a; }
.btn-secondary { padding: 6px 12px; background: white; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; font-weight: 500; cursor: pointer; }
.btn-secondary:hover { background: #f8fafc; }
.table-placeholder { padding: 60px; text-align: center; color: #94a3b8; background: #f8fafc; }
.loading-state { padding: 50px; text-align: center; color: #64748b; font-weight: 500; }

/* Responsive */
@media (max-width: 768px) {
    .app-layout { flex-direction: column; }
    .sidebar { width: 100%; height: auto; flex-direction: row; justify-content: space-between; align-items: center; border-right: none; border-bottom: 1px solid #e2e8f0; }
    .nav-menu, .sidebar-footer { display: none; }
    .dashboard-grid { grid-template-columns: 1fr; }
}
</style>