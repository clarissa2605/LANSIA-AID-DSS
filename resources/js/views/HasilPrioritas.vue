<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Hasil Prioritas</h2>
                <p class="text-muted mb-0">
                    Urutan lansia berdasarkan kebutuhan bantuan
                </p>
            </div>
            <button class="btn btn-dark">Export PDF</button>
        </div>

        <!-- TOP 3 -->
        <div class="row-metrics mb-4">
            <div class="metric-card accent-red">
                <div class="metric-val">{{ topThree[0]?.rank || "#1" }}</div>
                <div class="metric-lbl">{{ topThree[0]?.nama || "-" }}</div>
                <div class="metric-sub">{{ topThree[0]?.sub || "-" }}</div>
            </div>
            <div class="metric-card accent-gold">
                <div class="metric-val">{{ topThree[1]?.rank || "#2" }}</div>
                <div class="metric-lbl">{{ topThree[1]?.nama || "-" }}</div>
                <div class="metric-sub">{{ topThree[1]?.sub || "-" }}</div>
            </div>
            <div class="metric-card accent-blue">
                <div class="metric-val">{{ topThree[2]?.rank || "#3" }}</div>
                <div class="metric-lbl">{{ topThree[2]?.nama || "-" }}</div>
                <div class="metric-sub">{{ topThree[2]?.sub || "-" }}</div>
            </div>
        </div>

        <!-- FULL RANKING -->
        <div class="dashboard-preview">
            <div class="dss-flex-between mb-3">
                <h6 class="section-title mb-0">Ranking Lengkap</h6>
                <span class="text-muted small">{{
                    loading ? "Memuat..." : "Update terbaru"
                }}</span>
            </div>

            <div class="priority-list">
                <div
                    v-for="item in ranking"
                    :key="item.rank"
                    class="priority-item"
                >
                    <div class="priority-rank" :class="item.rankClass">
                        {{ item.rank }}
                    </div>
                    <div class="priority-info">
                        <div class="priority-name">{{ item.nama }}</div>
                        <div class="priority-sub">{{ item.info }}</div>
                    </div>
                    <div class="priority-score">
                        <div class="score-val">{{ item.skor }}</div>
                        <div class="score-bar">
                            <div
                                class="score-fill"
                                :class="item.fill"
                                :style="{ width: item.width }"
                            ></div>
                        </div>
                    </div>
                    <div>
                        <span
                            class="badge rounded-pill px-3 py-2"
                            :class="item.badgeClass"
                            >{{ item.status }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { rankingService } from "../services/rankingService.js";

const loading = ref(false);
const error = ref("");
const ranking = ref([]);

const topThree = computed(() => ranking.value.slice(0, 3));

onMounted(() => {
    loadRanking();
});

async function loadRanking() {
    loading.value = true;
    error.value = "";

    try {
        const response = await rankingService.getAll();
        ranking.value = (response.data || []).map(mapRankingItem);
    } catch (err) {
        console.error("FULL ERROR:", err);
        console.error("RESPONSE:", err.response);
        console.error("DATA:", err.response?.data);

        error.value = err?.response?.data?.message || "Gagal memuat ranking.";
    }
    {
        loading.value = false;
    }
}

function mapRankingItem(item) {
    const skor = Number(item.skor || 0).toFixed(2);

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
        info: `${item.umur || "-"} th - ${item.status_tinggal || "-"} - ${item.kecamatan || "-"}`,
        skor,
        sub: `${skor} - ${item.status}`,
        fill:
            item.rank <= 1
                ? "fill-red"
                : item.rank === 2
                  ? "fill-gold"
                  : item.rank === 3
                    ? "fill-green"
                    : "fill-muted",
        width: `${Math.min((Number(item.skor || 0) / 5) * 100, 100)}%`,
        status: item.status,
        badgeClass: getBadge(item.status),
    };
}

function getBadge(status) {
    if (status === "Prioritas Utama") return "bg-danger";
    if (status === "Diprioritaskan") return "bg-warning text-dark";
    if (status === "Cukup") return "bg-primary";
    return "bg-secondary";
}
</script>
