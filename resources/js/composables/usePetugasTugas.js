import { ref } from "vue";
import { petugasService } from "../services/petugasService";

export function usePetugasTugas() {
    const tugas = ref([]);
    const loading = ref(false);

    async function loadTugas() {
        loading.value = true;

        try {
            tugas.value = await petugasService.tugas();
        } finally {
            loading.value = false;
        }
    }

    return {
        tugas,
        loading,
        loadTugas,
    };
}
