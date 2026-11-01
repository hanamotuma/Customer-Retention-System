//
import './bootstrap';
import { createApp } from 'vue';
import Dashboard from './components/dashboard.vue';

const app = createApp({});
app.component('dashboard-component', Dashboard);
app.mount('#app');