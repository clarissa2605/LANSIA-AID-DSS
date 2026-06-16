import { ref } from "vue";
import { petugasService } from "../services/petugasService";

export function usePetugasMonitoring() {
    const monitoring = ref([]);
    const loading = ref(false);

    async function loadMonitoring() {
        loading.value = true;

        try {
            monitoring.value = await petugasService.monitoring();
        } finally {
            loading.value = false;
        }
    }

    return {
        monitoring,
        loading,
        loadMonitoring,
    };
}
