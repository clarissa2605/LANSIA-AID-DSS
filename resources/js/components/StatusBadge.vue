<template>
    <span class="badge rounded-pill px-3 py-2" :class="badgeClass">
        {{ displayText }}
    </span>
</template>

<script setup>
import { computed } from "vue";
import { useStatusColors } from "../composables/useStatusColors.js";

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        enum: ["status", "priority", "urgency"],
        default: "status",
    },
    // Override display text if needed
    label: {
        type: String,
        default: null,
    },
});

const {
    getStatusBadgeClass,
    getPriorityBadgeClass,
    getUrgencyBadgeClass,
    formatStatusDisplay,
} = useStatusColors();

const badgeClass = computed(() => {
    if (props.type === "priority") {
        return getPriorityBadgeClass(props.status);
    } else if (props.type === "urgency") {
        return getUrgencyBadgeClass(props.status);
    } else {
        return getStatusBadgeClass(props.status);
    }
});

const displayText = computed(() => {
    return props.label || formatStatusDisplay(props.status);
});
</script>
