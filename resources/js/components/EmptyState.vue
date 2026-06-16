<template>
    <div class="empty-state-container">
        <div class="empty-state-icon">
            <component :is="iconComponent" :size="48" :stroke-width="1" />
        </div>
        <h6 class="empty-state-title">{{ title }}</h6>
        <p class="empty-state-message">{{ message }}</p>
        <div v-if="$slots.action" class="empty-state-action">
            <slot name="action" />
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import {
    AlertCircle,
    Package,
    Users,
    Zap,
    BarChart3,
    Activity,
} from "lucide-vue-next";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        default: "alert",
        enum: ["alert", "package", "users", "zap", "chart", "activity"],
    },
});

const iconMap = {
    alert: AlertCircle,
    package: Package,
    users: Users,
    zap: Zap,
    chart: BarChart3,
    activity: Activity,
};

const iconComponent = computed(() => iconMap[props.icon] || AlertCircle);
</script>

<style scoped>
.empty-state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    color: var(--muted);
}

.empty-state-icon {
    margin-bottom: 16px;
    opacity: 0.5;
    color: var(--muted);
}

.empty-state-title {
    margin: 0 0 8px 0;
    color: var(--navy);
    font-weight: 600;
}

.empty-state-message {
    margin: 0 0 16px 0;
    font-size: 13px;
    color: var(--muted);
    max-width: 320px;
}

.empty-state-action {
    display: flex;
    gap: 8px;
    justify-content: center;
}
</style>
