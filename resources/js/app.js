import { createApp } from 'vue';
import Dashboard from './components/dashboard.vue';

createApp(Dashboard, {
    initialCustomers: window.initialCustomers ?? [],
}).mount('#app');