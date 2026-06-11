<template>
    <div class="loading-state-container">
        <div class="loading-spinner"></div>
        <p class="loading-text">{{ message }}</p>
    </div>
</template>

<script setup>
defineProps({
    message: {
        type: String,
        default: "Memuat data...",
    },
});
</script>

<style scoped>
.loading-state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
}

.loading-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid rgba(33, 37, 41, 0.1);
    border-top-color: var(--navy);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-text {
    margin: 0;
    font-size: 13px;
    color: var(--muted);
}
</style>
