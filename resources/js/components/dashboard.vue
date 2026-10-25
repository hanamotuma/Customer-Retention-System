<template>
    <div class="layout">
        <aside class="sidebar" :class="{ open: sidebarOpen }">
            <div class="brand">
                <div class="brand-mark"><i class="fa-solid fa-chart-pie"></i></div>
                <span class="brand-text">Retain<span>X</span></span>
            </div>

            <nav class="nav">
                <button
                    v-for="item in navItems"
                    :key="item.id"
                    class="nav-item"
                    :class="{ active: activeNav === item.id }"
                    :title="item.label"
                    @click="goTo(item.id)"
                >
                    <i :class="['fa-solid', item.icon]"></i>
                    <span class="nav-label">{{ item.label }}</span>
                </button>
            </nav>

            <div class="sidebar-foot">
                <img src="https://ui-avatars.com/api/?name=Admin&background=8b5cf6&color=fff" alt="Admin" />
            </div>
        </aside>

        <div v-if="sidebarOpen" class="overlay" @click="sidebarOpen = false"></div>

        <div class="content">
            <header class="topbar">
                <button class="menu-btn" type="button" aria-label="Open menu" @click="sidebarOpen = true">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="page-title">
                    <p>Dashboard</p>
                    <h1>Customer Retention System</h1>
                </div>

                <div class="topbar-actions">
                    <label class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input v-model="searchQuery" type="search" placeholder="Search customers or IDs..." />
                        <button v-if="searchQuery" class="clear-btn" type="button" aria-label="Clear search" @click="searchQuery = ''">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </label>

                    <div class="avatar-wrap">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=8b5cf6&color=fff" alt="Admin" />
                        <span class="status-dot"></span>
                    </div>
                </div>
            </header>

            <main class="main">
                <section id="overview" class="stats-grid">
                    <article class="stat-card primary">
                        <div class="stat-top">
                            <span class="stat-label">Total Managed Customers</span>
                            <span class="pill"><span class="dot-pulse"></span> Live</span>
                        </div>
                        <div>
                            <div class="stat-value">{{ customers.length }}</div>
                            <p class="stat-hint">AI processed successfully</p>
                        </div>
                    </article>

                    <article class="stat-card danger">
                        <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <span class="stat-label">High Risk Customers</span>
                        <div class="stat-value">{{ highRiskCount }}</div>
                        <div class="progress"><div class="progress-fill" :style="{ width: highRiskPercent + '%' }"></div></div>
                    </article>

                    <article class="stat-card safe">
                        <div class="stat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <span class="stat-label">Safe Customers</span>
                        <div class="stat-value">{{ lowRiskCount }}</div>
                        <div class="progress"><div class="progress-fill" :style="{ width: lowRiskPercent + '%' }"></div></div>
                    </article>

                    <article class="stat-card warn">
                        <div class="stat-icon"><i class="fa-solid fa-chart-pie"></i></div>
                        <span class="stat-label">Avg. Churn Probability</span>
                        <div class="stat-value">{{ avgChurn }}%</div>
                        <div class="progress"><div class="progress-fill" :style="{ width: avgChurn + '%' }"></div></div>
                    </article>
                </section>

                <div class="content-grid">
                    <section id="customers" class="panel table-panel">
                        <div class="panel-head">
                            <div>
                                <h2>Customer Risk Profiles</h2>
                                <p class="muted">{{ filteredCustomers.length }} of {{ customers.length }} customers</p>
                            </div>

                            <div class="filter-tabs">
                                <button
                                    v-for="filter in filters"
                                    :key="filter.value"
                                    type="button"
                                    :class="{ active: riskFilter === filter.value }"
                                    @click="riskFilter = filter.value"
                                >
                                    {{ filter.label }} <span class="count">{{ filter.count }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="table-scroll">
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
                                    <tr v-for="(customer, index) in visibleCustomers" :key="customer.id ?? index">
                                        <td>
                                            <div class="customer-cell">
                                                <span class="mini-avatar" :style="{ background: avatarColor(customer.name) }">
                                                    {{ initials(customer.name) }}
                                                </span>
                                                <div>
                                                    <strong>{{ customer.name || 'Unknown customer' }}</strong>
                                                    <small>ID #{{ customer.id ?? '—' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ customer.recency ?? 0 }} days</td>
                                        <td>{{ customer.frequency ?? 0 }} orders</td>
                                        <td>{{ formatCurrency(customer.monetary) }}</td>
                                        <td>
                                            <span v-if="customer.risk_label === 'High Risk'" class="badge badge-high">High Risk</span>
                                            <span v-else-if="customer.risk_label === 'Moderate Risk'" class="badge badge-mod">Moderate</span>
                                            <span v-else class="badge badge-low">Low Risk</span>
                                        </td>
                                    </tr>
                                    <tr v-if="visibleCustomers.length === 0">
                                        <td colspan="5" class="empty">No customers match your search or filters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="filteredCustomers.length > pageSize" class="table-foot">
                            <button class="ghost-btn" type="button" @click="showAll = !showAll">
                                {{ showAll ? 'Show less' : 'View all ' + filteredCustomers.length + ' customers' }}
                            </button>
                        </div>
                    </section>

                    <aside id="analytics" class="analytics">
                        <section class="panel chart-panel">
                            <div class="panel-head compact">
                                <h2>Risk Distribution</h2>
                                <span class="muted">{{ customers.length }} total</span>
                            </div>

                            <div class="chart-wrap">
                                <canvas ref="chartCanvas"></canvas>
                                <div class="chart-center">
                                    <strong>{{ customers.length }}</strong>
                                    <span>Customers</span>
                                </div>
                            </div>

                            <div class="legend">
                                <div v-for="item in chartLegend" :key="item.label" class="legend-item">
                                    <span class="legend-dot" :style="{ background: item.color }"></span>
                                    {{ item.label }}
                                    <strong>{{ item.value }}</strong>
                                </div>
                            </div>
                        </section>

                        <section class="panel engine-panel">
                            <h2>R Engine Status</h2>

                            <div class="engine-status">
                                <div class="engine-icon"><i class="fa-solid fa-check"></i></div>
                                <div>
                                    <h4>Model Trained</h4>
                                    <p>RFM Algorithm Active</p>
                                </div>
                                <span class="live-badge">Active</span>
                            </div>

                            <div class="engine-meta">
                                <div>
                                    <span>Algorithm</span>
                                    <strong>RFM</strong>
                                </div>
                                <div>
                                    <span>Model</span>
                                    <strong>Churn v1</strong>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    initialCustomers: {
        type: Array,
        default: () => [],
    },
});

const customers = ref(props.initialCustomers);

const searchQuery = ref('');
const riskFilter = ref('all');
const showAll = ref(false);
const sidebarOpen = ref(false);
const activeNav = ref('overview');
const pageSize = 8;

const navItems = [
    { id: 'overview', icon: 'fa-house', label: 'Overview' },
    { id: 'customers', icon: 'fa-users', label: 'Customers' },
    { id: 'analytics', icon: 'fa-chart-line', label: 'Analytics' },
];

const highRiskCount = computed(() => customers.value.filter((c) => c.risk_label === 'High Risk').length);
const moderateRiskCount = computed(() => customers.value.filter((c) => c.risk_label === 'Moderate Risk').length);
const lowRiskCount = computed(() => customers.value.filter((c) => c.risk_label === 'Low Risk').length);

const highRiskPercent = computed(() => percentOf(highRiskCount.value));
const lowRiskPercent = computed(() => percentOf(lowRiskCount.value));

const avgChurn = computed(() => {
    if (customers.value.length === 0) return 0;
    const total = customers.value.reduce((sum, c) => sum + Number.parseFloat(c.churn_probability || 0), 0);
    return Number((total / customers.value.length).toFixed(1));
});

const riskBreakdown = computed(() => ({
    high: highRiskCount.value,
    moderate: moderateRiskCount.value,
    low: lowRiskCount.value,
}));

const chartLegend = computed(() => [
    { label: 'High risk', value: riskBreakdown.value.high, color: '#ef4444' },
    { label: 'Moderate risk', value: riskBreakdown.value.moderate, color: '#f59e0b' },
    { label: 'Low risk', value: riskBreakdown.value.low, color: '#10b981' },
]);

const filters = computed(() => [
    { label: 'All', value: 'all', count: customers.value.length },
    { label: 'High', value: 'High Risk', count: highRiskCount.value },
    { label: 'Moderate', value: 'Moderate Risk', count: moderateRiskCount.value },
    { label: 'Low', value: 'Low Risk', count: lowRiskCount.value },
]);

const filteredCustomers = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    let list = customers.value;

    if (riskFilter.value !== 'all') {
        list = list.filter((c) => c.risk_label === riskFilter.value);
    }

    if (query) {
        list = list.filter((c) => {
            const name = String(c.name ?? '').toLowerCase();
            const id = String(c.id ?? '');
            return name.includes(query) || id.includes(query);
        });
    }

    return list;
});

const visibleCustomers = computed(() => (showAll.value ? filteredCustomers.value : filteredCustomers.value.slice(0, pageSize)));

watch([searchQuery, riskFilter], () => {
    showAll.value = false;
});

function percentOf(value) {
    if (customers.value.length === 0) return 0;
    return Number(((value / customers.value.length) * 100).toFixed(1));
}

function goTo(id) {
    activeNav.value = id;
    sidebarOpen.value = false;
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function formatCurrency(value) {
    const amount = Number.parseFloat(value) || 0;
    return amount.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0,
    });
}

function initials(name) {
    const parts = String(name ?? 'NA').trim().split(/\s+/).filter(Boolean);
    if (parts.length === 0) return '?';
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

const avatarPalette = ['#8b5cf6', '#ec4899', '#0ea5e9', '#10b981', '#f59e0b', '#6366f1', '#14b8a6'];

function avatarColor(name) {
    const seed = String(name ?? '').split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
    return avatarPalette[seed % avatarPalette.length];
}

const chartCanvas = ref(null);
let chartInstance = null;

watch(
    riskBreakdown,
    (value) => {
        if (!chartInstance) return;
        chartInstance.data.datasets[0].data = [value.high, value.moderate, value.low];
        chartInstance.update();
    },
    { deep: true }
);

onMounted(() => {
    const context = chartCanvas.value?.getContext('2d');
    if (!context) return;

    chartInstance = new Chart(context, {
        type: 'doughnut',
        data: {
            labels: ['High', 'Moderate', 'Low'],
            datasets: [
                {
                    data: [riskBreakdown.value.high, riskBreakdown.value.moderate, riskBreakdown.value.low],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 10,
                    displayColors: true,
                    boxPadding: 4,
                },
            },
        },
    });
});

onBeforeUnmount(() => {
    chartInstance?.destroy();
    chartInstance = null;
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
    --purple: #8b5cf6;
    --purple-dark: #6d28d9;
    --red: #ef4444;
    --green: #10b981;
    --amber: #f59e0b;
    --ink: #1e293b;
    --muted: #64748b;
    --line: #eef0f5;
    --bg: #eef1f6;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Poppins', system-ui, sans-serif;
    background: var(--bg);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
}

button,
input {
    font-family: inherit;
}

.layout {
    display: flex;
    width: 100%;
    min-height: 100dvh;
}

.sidebar {
    position: sticky;
    top: 0;
    z-index: 50;
    display: flex;
    flex: 0 0 92px;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 92px;
    height: 100dvh;
    padding: 22px 14px;
    background: #fff;
    border-right: 1px solid var(--line);
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 26px;
}

.brand-mark {
    display: grid;
    place-items: center;
    width: 44px;
    height: 44px;
    font-size: 1.15rem;
    color: #fff;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    border-radius: 14px;
    box-shadow: 0 10px 22px rgba(139, 92, 246, 0.35);
    flex: 0 0 44px;
}

.brand-text {
    display: none;
    font-size: 1.15rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.brand-text span {
    color: var(--purple);
}

.nav {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.nav-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 50px;
    height: 50px;
    font-size: 1.05rem;
    color: #94a3b8;
    background: transparent;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    transition: 0.2s ease;
}

.nav-item:hover {
    color: var(--purple);
    background: #f5f3ff;
}

.nav-item.active {
    color: #fff;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    box-shadow: 0 10px 20px rgba(139, 92, 246, 0.32);
}

.nav-label {
    display: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.sidebar-foot {
    margin-top: auto;
}

.sidebar-foot img {
    width: 44px;
    height: 44px;
    border: 2px solid #fff;
    border-radius: 50%;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
}

.overlay {
    display: none;
}

.content {
    display: flex;
    flex: 1;
    flex-direction: column;
    min-width: 0;
}

.topbar {
    position: sticky;
    top: 0;
    z-index: 30;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px clamp(16px, 3vw, 36px);
    background: rgba(238, 241, 246, 0.88);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(12px);
}

.menu-btn {
    display: none;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    font-size: 1.05rem;
    color: var(--ink);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 13px;
    cursor: pointer;
    flex: 0 0 44px;
}

.page-title {
    min-width: 0;
}

.page-title p {
    font-size: 0.78rem;
    letter-spacing: 0.4px;
    color: var(--muted);
    text-transform: uppercase;
}

.page-title h1 {
    font-size: clamp(1.05rem, 2vw, 1.55rem);
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.topbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: auto;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 240px;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.03);
}

.search-box i {
    font-size: 0.9rem;
    color: #94a3b8;
}

.search-box input {
    width: 100%;
    font-size: 0.88rem;
    color: var(--ink);
    background: transparent;
    border: none;
    outline: none;
}

.search-box input::placeholder {
    color: #a3aec0;
}

.clear-btn {
    display: grid;
    place-items: center;
    width: 22px;
    height: 22px;
    font-size: 0.7rem;
    color: #64748b;
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    flex: 0 0 22px;
}

.avatar-wrap {
    position: relative;
    flex: 0 0 44px;
}

.avatar-wrap img {
    display: block;
    width: 44px;
    height: 44px;
    border-radius: 50%;
}

.status-dot {
    position: absolute;
    right: 1px;
    bottom: 1px;
    width: 11px;
    height: 11px;
    background: var(--green);
    border: 2px solid #fff;
    border-radius: 50%;
}

.main {
    display: flex;
    flex-direction: column;
    gap: 22px;
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: clamp(16px, 3vw, 34px);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 18px;
    scroll-margin-top: 100px;
}

.stat-card {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 160px;
    padding: 22px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(226, 232, 240, 0.7);
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
}

.stat-card.primary {
    justify-content: space-between;
    color: #fff;
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    border: none;
}

.stat-card.primary::after {
    content: '';
    position: absolute;
    right: -40px;
    bottom: -60px;
    width: 170px;
    height: 170px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
}

.stat-top {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.stat-label {
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--muted);
}

.primary .stat-label {
    color: rgba(255, 255, 255, 0.85);
}

.stat-value {
    font-size: clamp(1.7rem, 3vw, 2.35rem);
    font-weight: 700;
    line-height: 1.1;
}

.stat-hint {
    font-size: 0.8rem;
    opacity: 0.8;
}

.stat-icon {
    display: grid;
    place-items: center;
    width: 46px;
    height: 46px;
    font-size: 1.05rem;
    color: var(--purple);
    background: #f3e8ff;
    border-radius: 14px;
}

.stat-card.danger .stat-icon {
    color: var(--red);
    background: #fee2e2;
}

.stat-card.safe .stat-icon {
    color: var(--green);
    background: #dcfce7;
}

.stat-card.warn .stat-icon {
    color: var(--amber);
    background: #fef3c7;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    font-size: 0.72rem;
    font-weight: 500;
    color: #fff;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 999px;
}

.dot-pulse {
    width: 7px;
    height: 7px;
    background: #a7f3d0;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(167, 243, 208, 0.7);
    animation: pulse 1.8s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(167, 243, 208, 0.7);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(167, 243, 208, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(167, 243, 208, 0);
    }
}

.progress {
    height: 7px;
    margin-top: auto;
    overflow: hidden;
    background: #f1f5f9;
    border-radius: 999px;
}

.progress-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.5s ease;
}

.danger .progress-fill {
    background: var(--red);
}

.safe .progress-fill {
    background: var(--green);
}

.warn .progress-fill {
    background: var(--amber);
}

.content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 22px;
    align-items: start;
}

.panel {
    padding: 22px;
    background: #fff;
    border: 1px solid rgba(226, 232, 240, 0.7);
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
}

.panel-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 16px;
}

.panel-head h2 {
    font-size: 1.05rem;
    font-weight: 600;
}

.panel-head.compact {
    align-items: center;
}

.muted {
    margin-top: 2px;
    font-size: 0.78rem;
    color: var(--muted);
}

.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-tabs button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    font-size: 0.76rem;
    font-weight: 500;
    color: var(--muted);
    background: #f8fafc;
    border: 1px solid var(--line);
    border-radius: 999px;
    cursor: pointer;
    transition: 0.2s ease;
}

.filter-tabs button:hover {
    color: var(--purple);
    border-color: #ddd6fe;
}

.filter-tabs button.active {
    color: #fff;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    border-color: transparent;
    box-shadow: 0 8px 18px rgba(139, 92, 246, 0.28);
}

.count {
    padding: 1px 7px;
    font-size: 0.7rem;
    background: rgba(15, 23, 42, 0.06);
    border-radius: 999px;
}

.filter-tabs button.active .count {
    background: rgba(255, 255, 255, 0.22);
}

.table-scroll {
    margin: 0 -6px;
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 640px;
    text-align: left;
    border-collapse: collapse;
}

th {
    padding: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.6px;
    color: var(--muted);
    text-transform: uppercase;
    border-bottom: 1px solid var(--line);
}

td {
    padding: 12px;
    font-size: 0.86rem;
    white-space: nowrap;
    border-bottom: 1px solid var(--line);
}

tbody tr {
    transition: background 0.15s ease;
}

tbody tr:hover {
    background: #fafaff;
}

.customer-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mini-avatar {
    display: grid;
    place-items: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #fff;
    border-radius: 11px;
}

.customer-cell small {
    display: block;
    font-size: 0.7rem;
    color: #94a3b8;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 999px;
}

.badge-high {
    color: #ef4444;
    background: #fee2e2;
}

.badge-mod {
    color: #f59e0b;
    background: #fef3c7;
}

.badge-low {
    color: #10b981;
    background: #d1fae5;
}

.empty {
    padding: 34px 12px !important;
    font-size: 0.86rem;
    color: var(--muted);
    text-align: center;
}

.table-foot {
    display: flex;
    justify-content: center;
    padding-top: 16px;
}

.ghost-btn {
    padding: 9px 18px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--purple);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 999px;
    cursor: pointer;
    transition: 0.2s ease;
}

.ghost-btn:hover {
    background: #f5f3ff;
    border-color: #ddd6fe;
}

.analytics {
    display: flex;
    flex-direction: column;
    gap: 22px;
    scroll-margin-top: 100px;
}

.chart-wrap {
    position: relative;
    height: 210px;
    margin: 6px 0 18px;
}

.chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    pointer-events: none;
    transform: translate(-50%, -50%);
}

.chart-center strong {
    font-size: 1.6rem;
    line-height: 1;
}

.chart-center span {
    font-size: 0.7rem;
    color: var(--muted);
}

.legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: var(--muted);
}

.legend-item strong {
    margin-left: auto;
    font-size: 0.85rem;
    color: var(--ink);
}

.legend-dot {
    flex: 0 0 9px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
}

.engine-panel h2 {
    margin-bottom: 14px;
    font-size: 1.05rem;
}

.engine-status {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: #f8fafc;
    border-radius: 16px;
}

.engine-icon {
    display: grid;
    place-items: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    font-size: 0.9rem;
    color: var(--green);
    background: #d1fae5;
    border-radius: 50%;
}

.engine-status h4 {
    font-size: 0.85rem;
}

.engine-status p {
    font-size: 0.72rem;
    color: var(--muted);
}

.live-badge {
    margin-left: auto;
    padding: 4px 9px;
    font-size: 0.66rem;
    font-weight: 600;
    color: var(--green);
    background: #dcfce7;
    border-radius: 999px;
}

.engine-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 14px;
}

.engine-meta div {
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 14px;
}

.engine-meta span {
    display: block;
    font-size: 0.68rem;
    color: var(--muted);
}

.engine-meta strong {
    font-size: 0.82rem;
}

@media (max-width: 1100px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .analytics {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        align-items: flex-start;
        flex-basis: 220px;
        width: 220px;
        padding: 22px 18px;
        transform: translateX(-100%);
        transition: transform 0.25s ease;
        box-shadow: 20px 0 50px rgba(15, 23, 42, 0.12);
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .brand-text {
        display: inline;
    }

    .nav {
        align-items: stretch;
    }

    .nav-item {
        justify-content: flex-start;
        width: 100%;
        padding: 0 14px;
    }

    .nav-label {
        display: inline;
    }

    .sidebar-foot {
        display: block;
    }

    .overlay {
        position: fixed;
        inset: 0;
        z-index: 45;
        display: block;
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(2px);
    }

    .menu-btn {
        display: inline-flex;
    }

    .topbar {
        flex-wrap: wrap;
    }

    .page-title {
        flex: 1;
    }

    .topbar-actions {
        width: 100%;
    }

    .search-box {
        flex: 1;
        min-width: 0;
    }
}

@media (max-width: 640px) {
    .analytics {
        grid-template-columns: 1fr;
    }

    .stat-card {
        min-height: auto;
    }

    th,
    td {
        padding: 11px 10px;
    }

    .filter-tabs {
        width: 100%;
    }
}
</style>
