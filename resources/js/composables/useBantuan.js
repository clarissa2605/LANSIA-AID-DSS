import { ref, computed } from "vue";
import { petugasService } from "../services/petugasService";

export function useBantuan() {
    const loading = ref(false);
    const error = ref("");

    const bantuan = ref([]);

    async function loadBantuan() {
        loading.value = true;
        error.value = "";

        try {
            bantuan.value = await petugasService.bantuan();
        } catch (err) {
            console.error(err);

            error.value =
                err?.response?.data?.message || "Gagal memuat data bantuan";
        } finally {
            loading.value = false;
        }
    }

    async function verifikasi(id) {
        await petugasService.verifikasi(id);
        await loadBantuan();
    }

    async function salurkan(id) {
        await petugasService.salurkan(id);
        await loadBantuan();
    }

    async function tolak(id) {
        await petugasService.tolak(id);
        await loadBantuan();
    }

    const totalPending = computed(
        () => bantuan.value.filter((item) => item.status === "pending").length,
    );

    const totalDiproses = computed(
        () => bantuan.value.filter((item) => item.status === "diproses").length,
    );

    const totalDisalurkan = computed(
        () =>
            bantuan.value.filter((item) => item.status === "disalurkan").length,
    );

    return {
        loading,
        error,

        bantuan,

        totalPending,
        totalDiproses,
        totalDisalurkan,

        loadBantuan,
        verifikasi,
        salurkan,
        tolak,
    };
}
