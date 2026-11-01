<template>
    <div class="auth-container">
        <!-- Left Side: Branding (Hidden on Mobile) -->
        <div class="auth-branding">
            <div class="brand-logo">■ RetentionOS</div>
            <div class="brand-text">
                <h1>Customer Retention System</h1>
                <p>AI-driven churn prediction and revenue analytics for modern businesses.</p>
            </div>
            <div class="brand-footer">© 2026 RetentionOS Inc.</div>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-form-wrapper">
            <div class="auth-form">
                <h2>{{ isLogin ? 'Sign in to your account' : 'Create a new account' }}</h2>
                
                <div v-if="error" class="error-alert">{{ error }}</div>

                <form @submit.prevent="submit">
                    <div v-if="!isLogin" class="form-group">
                        <label>Full Name</label>
                        <input v-model="form.name" type="text" required placeholder="John Doe" />
                    </div>
                    
                    <div class="form-group">
                        <label>Email address</label>
                        <input v-model="form.email" type="email" required placeholder="john@company.com" />
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input v-model="form.password" type="password" required placeholder="••••••••" />
                    </div>

                    <button type="submit" :disabled="loading" class="btn-primary">
                        {{ loading ? 'Authenticating...' : (isLogin ? 'Sign in' : 'Create account') }}
                    </button>
                </form>

                <p class="auth-toggle">
                    {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
                    <a href="#" @click.prevent="isLogin = !isLogin; error = null">
                        {{ isLogin ? 'Sign up' : 'Sign in' }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const isLogin = ref(true);
const loading = ref(false);
const error = ref(null);
const form = ref({ name: '', email: '', password: '' });
const emit = defineEmits(['authenticated']);

const submit = async () => {
    error.value = null;
    loading.value = true;
    const endpoint = isLogin.value ? '/api/login' : '/api/register';
    
    try {
        const response = await axios.post(endpoint, form.value);
        localStorage.setItem('token', response.data.access_token);
        emit('authenticated');
    } catch (err) {
        error.value = err.response?.data?.message || "Authentication failed. Please check your credentials.";
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
/* Base styles */
.auth-container { display: flex; height: 100vh; font-family: 'Inter', system-ui, sans-serif; background: #ffffff; }
.auth-branding { flex: 1; background: #0f172a; color: white; padding: 60px; display: flex; flex-direction: column; justify-content: space-between; }
.brand-logo { font-size: 1.25rem; font-weight: 700; letter-spacing: 1px; }
.brand-text h1 { font-size: 2.5rem; font-weight: 600; margin-bottom: 20px; line-height: 1.2; }
.brand-text p { color: #94a3b8; font-size: 1.1rem; line-height: 1.6; max-width: 400px; }
.brand-footer { color: #64748b; font-size: 0.85rem; }
.auth-form-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; background: #fafafa; }
.auth-form { width: 100%; max-width: 400px; background: white; padding: 40px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
.auth-form h2 { font-size: 1.5rem; font-weight: 600; color: #0f172a; margin-bottom: 25px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 0.875rem; font-weight: 500; color: #334155; margin-bottom: 6px; }
.form-group input { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; outline: none; transition: border 0.2s; box-sizing: border-box;}
.form-group input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
.btn-primary { width: 100%; padding: 12px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 500; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
.btn-primary:hover { background: #4338ca; }
.btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }
.auth-toggle { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #64748b; }
.auth-toggle a { color: #4f46e5; text-decoration: none; font-weight: 500; }
.error-alert { background: #fef2f2; color: #b91c1c; padding: 10px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #fecaca; }

/* Responsive */
@media (max-width: 768px) {
    .auth-branding { display: none; }
    .auth-form-wrapper { background: white; padding: 20px; }
    .auth-form { border: none; box-shadow: none; padding: 0; }
}
</style>