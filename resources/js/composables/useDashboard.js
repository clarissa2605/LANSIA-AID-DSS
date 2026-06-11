import { ref } from "vue";
import { dashboardService } from "../services/dashboardService.js";

export function useDashboard() {
    const loading = ref(false);
    const error = ref("");

    const topPriority = ref([]);

    const statistik = ref({
        total_lansia: 0,
        sudah_dinilai: 0,
        belum_dinilai: 0,
        bantuan_disalurkan: 0,
    });

    const priorityStats = ref({
        tinggi: 0,
        sedang: 0,
        rendah: 0,
    });

    async function loadDashboard() {
        loading.value = true;
        error.value = "";

        try {
            const data = await dashboardService.getDashboard();

            console.log("DASHBOARD DATA", data);
            console.log("RANKING TERATAS", data.ranking_teratas);

            statistik.value = data.statistik;

            topPriority.value = (data.ranking_teratas || []).map(
                (item, index) => ({
                    rank: `#${item.rank}`,

                    rankClass:
                        index === 0
                            ? "rank-1"
                            : index === 1
                              ? "rank-2"
                              : index === 2
                                ? "rank-3"
                                : "rank-4",

                    nama: item.nama,

                    info: `${item.umur} th · ${item.kecamatan}`,

                    skor: Number(item.skor).toFixed(2),

                    fill:
                        item.priority_status === "Prioritas Utama"
                            ? "fill-red"
                            : item.priority_status === "Diprioritaskan"
                              ? "fill-gold"
                              : "fill-green",

                    width: `${Math.min((Number(item.skor) / 5) * 100, 100)}%`,

                    status: item.priority_status,
                }),
            );

            priorityStats.value = hitungPrioritas(data.ranking_teratas || []);
        } catch (err) {
            console.error(err);

            error.value =
                err?.response?.data?.message || "Gagal memuat dashboard";
        } finally {
            loading.value = false;
        }
    }
    function mapRankingItem(item) {
        return {
            rank: `#${item.rank}`,

            rankClass:
                item.rank <= 1
                    ? "rank-1"
                    : item.rank === 2
                      ? "rank-2"
                      : item.rank === 3
                        ? "rank-3"
                        : "rank-4",

            nama: item.nama,

            info:
                `${item.umur || "-"} th - ` +
                `${item.status_tinggal || "-"} - ` +
                `${item.kecamatan || "-"}`,

            skor: Number(item.skor || 0).toFixed(2),

            fill:
                item.rank <= 1
                    ? "fill-red"
                    : item.rank === 2
                      ? "fill-gold"
                      : item.rank === 3
                        ? "fill-green"
                        : "fill-muted",

            width: `${Math.min((Number(item.skor || 0) / 5) * 100, 100)}%`,
        };
    }

    function hitungPrioritas(items) {
        return items.reduce(
            (result, item) => {
                if (item.priority_status === "Prioritas Utama") {
                    result.tinggi++;
                } else if (item.priority_status === "Diprioritaskan") {
                    result.sedang++;
                } else {
                    result.rendah++;
                }

                return result;
            },
            {
                tinggi: 0,
                sedang: 0,
                rendah: 0,
            },
        );
    }

    return {
        loading,
        error,

        statistik,
        topPriority,
        priorityStats,

        loadDashboard,
    };
}
