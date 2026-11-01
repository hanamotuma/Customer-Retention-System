<template>
    <div class="table-container">
        <!-- Table Controls -->
        <div class="table-controls">
            <h2>All Customers</h2>
            <div class="controls-right">
                <input 
                    type="text" 
                    v-model="globalFilter" 
                    placeholder="Search customers..." 
                    class="search-input"
                />
                <select v-model="riskFilter" class="filter-select">
                    <option value="">All Risk Levels</option>
                    <option value="High Risk">High Risk</option>
                    <option value="Moderate Risk">Moderate Risk</option>
                    <option value="Low Risk">Low Risk</option>
                </select>
            </div>
        </div>

        <!-- The Table -->
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th @click="sortBy('name')" class="sortable">Customer Name <span v-if="sortKey === 'name'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span></th>
                        <th @click="sortBy('recency')" class="sortable">Recency (Days) <span v-if="sortKey === 'recency'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span></th>
                        <th @click="sortBy('frequency')" class="sortable">Frequency <span v-if="sortKey === 'frequency'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span></th>
                        <th @click="sortBy('monetary')" class="sortable">Monetary ($) <span v-if="sortKey === 'monetary'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span></th>
                        <th @click="sortBy('churn_probability')" class="sortable">Churn Prob. <span v-if="sortKey === 'churn_probability'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span></th>
                        <th>Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in paginatedData" :key="customer.id">
                        <td class="flex-name">
                            <img :src="'https://ui-avatars.com/api/?name=' + customer.name + '&background=random'" class="table-avatar" />
                            {{ customer.name }}
                        </td>
                        <td>{{ customer.recency }} days</td>
                        <td>{{ customer.frequency }} orders</td>
                        <td>${{ customer.monetary.toFixed(2) }}</td>
                        <td>{{ customer.churn_probability.toFixed(1) }}%</td>
                        <td>
                            <span class="badge" :class="getRiskBadge(customer.risk_label)">
                                {{ customer.risk_label }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="paginatedData.length === 0">
                        <td colspan="6" class="no-data">No customers found matching your criteria.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <span>Showing {{ startIndex + 1 }} to {{ Math.min(endIndex, filteredData.length) }} of {{ filteredData.length }} entries</span>
            <div class="page-buttons">
                <button @click="prevPage" :disabled="currentPage === 1" class="page-btn">Previous</button>
                <span class="page-info">Page {{ currentPage }} of {{ totalPages }}</span>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="page-btn">Next</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    customers: {
        type: Array,
        required: true
    }
});

const globalFilter = ref('');
const riskFilter = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

// Sorting State
const sortKey = ref('churn_probability');
const sortOrder = ref('desc');

// 1. Filter Logic
const filteredData = computed(() => {
    return props.customers.filter(customer => {
        const matchesSearch = customer.name.toLowerCase().includes(globalFilter.value.toLowerCase());
        const matchesRisk = riskFilter.value ? customer.risk_label === riskFilter.value : true;
        return matchesSearch && matchesRisk;
    }).sort((a, b) => {
        // 2. Sorting Logic
        let valA = a[sortKey.value];
        let valB = b[sortKey.value];
        
        if (typeof valA === 'string') {
            return sortOrder.value === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        }
        
        return sortOrder.value === 'asc' ? valA - valB : valB - valA;
    });
});

// Reset to page 1 when filtering
watch([globalFilter, riskFilter], () => {
    currentPage.value = 1;
});

// 3. Pagination Logic
const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage));
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => startIndex.value + itemsPerPage);
const paginatedData = computed(() => filteredData.value.slice(startIndex.value, endIndex.value));

const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };

// 4. Sorting Function
const sortBy = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortOrder.value = 'asc';
    }
};

const getRiskBadge = (label) => {
    if (label === 'High Risk') return 'badge-high';
    if (label === 'Moderate Risk') return 'badge-mod';
    return 'badge-low';
};
</script>

<style scoped>
.table-container { background: var(--bg-card); border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); margin-top: 20px; transition: background 0.3s; }
.table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.table-controls h2 { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; }
.controls-right { display: flex; gap: 10px; }
.search-input, .filter-select { padding: 8px 12px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-main); border-radius: 6px; font-size: 0.9rem; outline: none; }
.search-input:focus, .filter-select:focus { border-color: #3b82f6; }

.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); padding: 12px; border-bottom: 1px solid var(--border-color); font-weight: 600; }
.data-table th.sortable { cursor: pointer; user-select: none; }
.data-table th.sortable:hover { color: var(--text-main); }
.data-table td { padding: 12px; font-size: 0.9rem; border-bottom: 1px solid var(--border-color); color: var(--text-main); }
.flex-name { display: flex; align-items: center; gap: 10px; font-weight: 500; }
.table-avatar { width: 28px; height: 28px; border-radius: 50%; }
.no-data { text-align: center; padding: 30px; color: var(--text-muted); }

.badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-high { background: #fee2e2; color: #b91c1c; }
.badge-mod { background: #fef3c7; color: #b45309; }
.badge-low { background: #d1fae5; color: #047857; }

.pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 0.85rem; color: var(--text-muted); }
.page-buttons { display: flex; align-items: center; gap: 15px; }
.page-btn { padding: 6px 12px; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; font-weight: 500; color: var(--text-main); }
.page-btn:hover:not(:disabled) { background: var(--bg-card); }
.page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.page-info { font-weight: 500; color: var(--text-main); }
</style>