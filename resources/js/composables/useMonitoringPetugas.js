import { ref, computed } from "vue";
import { petugasService } from "../services/petugasService";

export function useMonitoringPetugas() {
    const loading = ref(false);
    const error = ref("");
    const monitoring = ref([]);

    async function loadMonitoring() {
        loading.value = true;
        error.value = "";

        try {
            monitoring.value = await petugasService.monitoring();
        } catch (err) {
            error.value =
                err?.response?.data?.message || "Gagal memuat data monitoring";

            console.error(err);
        } finally {
            loading.value = false;
        }
    }

    const metrics = computed(() => ({
        pending: monitoring.value.filter((i) => i.status === "pending").length,

        diproses: monitoring.value.filter((i) => i.status === "diproses")
            .length,

        disalurkan: monitoring.value.filter((i) => i.status === "disalurkan")
            .length,
    }));

    const progress = computed(() => {
        const total = monitoring.value.length;

        if (!total) return 0;

        return Math.round((metrics.value.disalurkan / total) * 100);
    });

    const monitorData = computed(() =>
        monitoring.value.map((item) => ({
            id: item.id,

            nama: item.nama,

            prioritas:
                item.urgensi === "tinggi"
                    ? "Prioritas Utama"
                    : item.urgensi === "sedang"
                      ? "Diprioritaskan"
                      : "Normal",

            prioClass:
                item.urgensi === "tinggi"
                    ? "bg-danger"
                    : item.urgensi === "sedang"
                      ? "bg-warning text-dark"
                      : "bg-secondary",

            jenis: item.jenis,

            status:
                item.status === "pending"
                    ? "Belum Disalurkan"
                    : item.status === "diproses"
                      ? "Diproses"
                      : "Disalurkan",

            statusClass:
                item.status === "diproses"
                    ? "bg-warning text-dark"
                    : item.status === "disalurkan"
                      ? "bg-success"
                      : "bg-secondary",

            catatan: item.catatan,

            tanggal: item.tanggal_pengajuan,
        })),
    );

    return {
        loading,
        error,

        monitorData,
        metrics,
        progress,

        loadMonitoring,
    };
}
