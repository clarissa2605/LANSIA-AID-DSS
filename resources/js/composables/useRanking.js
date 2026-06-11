import { ref } from "vue";
import { rankingService } from "../services/rankingService.js";

export function useRanking() {
    const loading = ref(false);
    const error = ref("");
    const warning = ref("");
    const status = ref(""); // 'loading', 'success', 'error', 'empty', 'warning'

    const hasil = ref([]);
    const incomplete = ref([]);

    const dataPenilaian = ref([]);
    const dataNormalisasi = ref([]); // placeholder
    const dataBobot = ref([]);

    const idealPositif = ref([]); // placeholder
    const idealNegatif = ref([]); // placeholder

    async function proses() {
        loading.value = true;
        error.value = "";
        warning.value = "";
        status.value = "loading";

        try {
            const response = await rankingService.hitung();

            // Handle response structure
            const responseData = response.data || response;

            // Check status
            if (responseData.status === "NO_COMPLETE_DATA") {
                status.value = "empty";
                warning.value =
                    "Tidak ada lansia dengan penilaian lengkap. Silakan lengkapi penilaian untuk semua lansia terlebih dahulu.";
                hasil.value = [];
                incomplete.value = responseData.incomplete || [];
                return;
            }

            if (responseData.status === "PARTIAL_DATA") {
                status.value = "warning";
                warning.value = `${responseData.incomplete_count} lansia belum memiliki penilaian lengkap dan tidak ditampilkan dalam ranking.`;
                incomplete.value = responseData.incomplete || [];
            }

            const ranking = responseData.data || responseData.ranking || [];

            if (!Array.isArray(ranking) || ranking.length === 0) {
                status.value = "empty";
                hasil.value = [];
                return;
            }

            hasil.value = ranking.map((item) => ({
                rank: item.rank,
                nama: item.nama,
                skor: Number(item.skor).toFixed(4),
                status: item.priority_status,
                badgeClass: getBadge(item.priority_status),
            }));

            // DATA PENILAIAN
            dataPenilaian.value = ranking.map((item) => {
                const row = [item.nama];

                if (item.detail && Array.isArray(item.detail)) {
                    item.detail.forEach((detail) => {
                        row.push(detail.nilai);
                    });
                }

                return row;
            });

            // DATA BOBOT
            dataBobot.value = ranking.map((item) => {
                const row = [item.nama];

                if (item.detail && Array.isArray(item.detail)) {
                    item.detail.forEach((detail) => {
                        row.push(Number(detail.nilai_akhir).toFixed(4));
                    });
                }

                return row;
            });

            // Ambil nilai maksimum & minimum dari hasil pembobotan
            if (dataBobot.value.length > 0) {
                const kolom = dataBobot.value[0].length - 1;

                const positif = [];
                const negatif = [];

                for (let i = 1; i <= kolom; i++) {
                    const values = dataBobot.value
                        .map((row) => Number(row[i]))
                        .filter((v) => !isNaN(v));

                    if (values.length > 0) {
                        positif.push(Math.max(...values).toFixed(4));
                        negatif.push(Math.min(...values).toFixed(4));
                    }
                }

                idealPositif.value = positif;
                idealNegatif.value = negatif;
            }

            status.value = "success";
        } catch (err) {
            status.value = "error";

            // Handle different error types
            if (err.isTimeout) {
                error.value =
                    "Permintaan timeout. Server tidak merespons. Silakan coba lagi.";
            } else if (err.isNetworkError) {
                error.value =
                    "Gagal terhubung ke server. Periksa koneksi internet Anda.";
            } else if (err.response?.status === 422) {
                error.value = err.response.data?.message || "Data tidak valid.";
            } else if (err.response?.status === 500) {
                error.value =
                    "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
            } else {
                error.value =
                    err?.response?.data?.message ||
                    err?.message ||
                    "Gagal menghitung ranking";
            }

            hasil.value = [];
            incomplete.value = [];

            console.error(err);
        } finally {
            loading.value = false;
        }
    }

    function getBadge(status) {
        if (status === "Prioritas Utama") {
            return "bg-danger";
        }

        if (status === "Diprioritaskan") {
            return "bg-warning text-dark";
        }

        if (status === "Cukup") {
            return "bg-primary";
        }

        return "bg-secondary";
    }

    return {
        loading,
        error,
        warning,
        status,

        hasil,
        incomplete,

        dataPenilaian,
        dataNormalisasi,
        dataBobot,

        idealPositif,
        idealNegatif,

        proses,
    };
}
