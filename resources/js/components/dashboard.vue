<template>
    <div style="padding: 50px; text-align: center; font-family: sans-serif;">
        <h1 style="color: #8b5cf6;">🚀 Vue.js is successfully integrated!</h1>
        <p>We are ready to build the new frontend.</p>
        <button @click="count++" style="padding: 10px 20px; font-size: 1rem; border-radius: 8px; background: #8b5cf6; color: white; border: none; cursor: pointer;">
            Click me: {{ count }}
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const count = ref(0);
</script>