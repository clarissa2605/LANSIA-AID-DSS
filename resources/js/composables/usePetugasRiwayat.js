import { ref } from "vue";
import { petugasService } from "../services/petugasService";

export function usePetugasRiwayat() {
    const riwayat = ref([]);
    const loading = ref(false);

    async function loadRiwayat() {
        loading.value = true;

        try {
            riwayat.value = await petugasService.riwayat();
        } finally {
            loading.value = false;
        }
    }

    return {
        riwayat,
        loading,
        loadRiwayat,
    };
}
