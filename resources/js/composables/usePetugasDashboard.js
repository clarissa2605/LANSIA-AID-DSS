import { ref, computed } from "vue";
import { petugasService } from "../services/petugasService";

export function usePetugasDashboard() {
    const loading = ref(false);
    const error = ref("");

    const dashboard = ref({
        total_lansia: 0,
        pending: 0,
        diproses: 0,
        disalurkan: 0,
        belum_dinilai: 0,
        ranking_teratas: [],
    });

    async function loadDashboard() {
        loading.value = true;

        try {
            const data = await petugasService.dashboard();

            console.log("FULL RESPONSE:", data);
            console.log("RANKING:", data.ranking_teratas);

            dashboard.value = data;
        } catch (err) {
            console.error(err);
        } finally {
            loading.value = false;
        }
    }

    const lansiaList = computed(() => {
        console.log("ranking_teratas =", dashboard.value.ranking_teratas);

        return (dashboard.value.ranking_teratas || [])
            .filter(
                (item) =>
                    typeof item === "object" && item !== null && item.nama,
            )
            .slice(0, 5)
            .map((item, index) => ({
                rank: `#${item.rank || index + 1}`,
                rankClass: `rank-${index + 1}`,
                nama: item.nama,
                info: `${item.umur ?? "-"} th · ${item.kecamatan ?? "-"}`,
                status:
                    item.priority_status ||
                    (index === 0
                        ? "Prioritas Utama"
                        : index < 3
                          ? "Diprioritaskan"
                          : "Normal"),
                statusClass:
                    item.priority_status === "Prioritas Utama"
                        ? "bg-danger"
                        : item.priority_status === "Diprioritaskan"
                          ? "bg-warning text-dark"
                          : "bg-secondary",
            }));
    });

    const statusItems = computed(() => [
        {
            label: "Pending",
            count: dashboard.value.pending,
            color: "#9ca3af",
        },
        {
            label: "Diproses",
            count: dashboard.value.diproses,
            color: "#fbbf24",
        },
        {
            label: "Disalurkan",
            count: dashboard.value.disalurkan,
            color: "#34d399",
        },
    ]);

    return {
        loading,
        error,

        dashboard,
        lansiaList,
        statusItems,

        loadDashboard,
    };
}
