<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Dashboard Petugas</h2>
                <p class="text-muted mb-0">
                    Selamat datang,
                    <strong>{{ auth.user?.name }}</strong>
                    — tugas Anda hari ini
                </p>
            </div>
            <router-link to="/petugas/lansia" class="btn btn-dark"
                >+ Tambah Data Lansia</router-link
            >
        </div>
        <div v-if="loading" class="alert alert-info mb-3">
            Memuat dashboard...
        </div>

        <div v-if="error" class="alert alert-danger mb-3">
            {{ error }}
        </div>

        <!-- METRICS -->
        <div class="row-metrics mb-4">
            <div class="metric-card accent-red">
                <div class="metric-top">
                    <AlertCircle :size="20" />
                    <span class="metric-badge badge-red">Segera</span>
                </div>
                <div class="metric-val">
                    {{ dashboard.pending }}
                </div>
                <div class="metric-lbl">Perlu Disalurkan Segera</div>
            </div>
            <div class="metric-card accent-gold">
                <div class="metric-top">
                    <Clock :size="20" />
                    <span class="metric-badge badge-gold">Proses</span>
                </div>
                <div class="metric-val">
                    {{ dashboard.diproses }}
                </div>
                <div class="metric-lbl">Sedang Diproses</div>
            </div>
            <div class="metric-card accent-green">
                <div class="metric-top">
                    <CheckCircle :size="20" />
                    <span class="metric-badge badge-green">Selesai</span>
                </div>
                <div class="metric-val">
                    {{ dashboard.disalurkan }}
                </div>
                <div class="metric-lbl">Sudah Disalurkan</div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="row-main">
            <!-- LANSIA TANGGUNG JAWAB -->
            <div class="col-left">
                <div class="dashboard-preview">
                    <div class="dss-flex-between mb-3">
                        <h6 class="section-title mb-0">Data Lansia</h6>
                        <router-link
                            to="/petugas/bantuan"
                            class="btn btn-outline-dark btn-sm"
                            >Lihat Semua</router-link
                        >
                    </div>

                    <div class="priority-list">
                        <div
                            v-if="lansiaList.length === 0"
                            class="text-center text-muted py-4"
                        >
                            Belum ada data ranking.
                        </div>
                        <div
                            v-for="item in lansiaList"
                            :key="item.nama"
                            class="priority-item"
                        >
                            <div class="priority-rank" :class="item.rankClass">
                                {{ item.rank }}
                            </div>
                            <div class="priority-info">
                                <div class="priority-name">{{ item.nama }}</div>
                                <div class="priority-sub">{{ item.info }}</div>
                            </div>
                            <div>
                                <span
                                    class="badge rounded-pill px-3 py-2"
                                    :class="item.statusClass"
                                >
                                    {{ item.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TUGAS HARI INI -->
                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Tugas Hari Ini</h6>
                    <div
                        style="display: flex; flex-direction: column; gap: 10px"
                    >
                        <div
                            v-for="t in tugasHariIni"
                            :key="t.label"
                            class="tugas-item"
                            :class="t.done ? 'done' : ''"
                        >
                            <div class="tugas-check">
                                <CheckCircle
                                    v-if="t.done"
                                    :size="16"
                                    style="color: var(--sage)"
                                />
                                <Circle
                                    v-else
                                    :size="16"
                                    style="color: var(--muted)"
                                />
                            </div>
                            <div class="tugas-info">
                                <div class="tugas-label">{{ t.label }}</div>
                                <div class="tugas-sub">{{ t.sub }}</div>
                            </div>
                            <router-link
                                v-if="!t.done"
                                :to="t.link"
                                class="btn btn-sm btn-outline-dark"
                            >
                                Kerjakan
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-right">
                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Status Distribusi Saya</h6>
                    <div
                        style="display: flex; flex-direction: column; gap: 10px"
                    >
                        <div
                            v-for="s in statusItems"
                            :key="s.label"
                            style="
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            "
                        >
                            <div
                                style="
                                    width: 8px;
                                    height: 8px;
                                    border-radius: 50%;
                                    flex-shrink: 0;
                                "
                                :style="{ background: s.color }"
                            ></div>
                            <span
                                style="
                                    flex: 1;
                                    font-size: 13px;
                                    color: var(--navy);
                                "
                                >{{ s.label }}</span
                            >
                            <strong
                                style="font-size: 13px; color: var(--navy)"
                                >{{ s.count }}</strong
                            >
                        </div>
                    </div>
                    <hr style="border-color: var(--border); margin: 14px 0" />
                    <div class="dss-flex-between">
                        <span style="font-size: 13px; color: var(--muted)">
                            Progress
                        </span>

                        <span
                            style="
                                font-size: 13px;
                                font-weight: 700;
                                color: var(--navy);
                            "
                        >
                            {{ progress }}%
                        </span>
                    </div>
                    <div class="progress-bar-wrap mt-2">
                        <div
                            class="progress-bar-fill"
                            :style="{
                                width: `${progress}%`,
                            }"
                        ></div>
                    </div>
                </div>

                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Statistik Lansia</h6>

                    <div class="dss-flex-between">
                        <span>Total Lansia</span>
                        <strong>{{ dashboard.total_lansia }}</strong>
                    </div>

                    <div class="dss-flex-between mt-2">
                        <span>Belum Dinilai</span>
                        <strong>{{ dashboard.belum_dinilai }}</strong>
                    </div>
                </div>

                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Aksi Cepat</h6>
                    <div style="display: grid; gap: 8px">
                        <router-link
                            to="/petugas/lansia"
                            class="btn btn-outline-dark"
                            >Tambah Data Lansia</router-link
                        >
                        <router-link
                            to="/petugas/bantuan"
                            class="btn btn-outline-dark"
                            >Update Status Penyaluran</router-link
                        >
                        <router-link
                            to="/petugas/monitoring"
                            class="btn btn-dark"
                            >Lihat Monitoring</router-link
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, computed } from "vue";
import { AlertCircle, Clock, CheckCircle, Circle } from "lucide-vue-next";

import { authStore as auth } from "../../store/auth.js";
import { usePetugasDashboard } from "../../composables/usePetugasDashboard.js";

const {
    loading,
    error,

    dashboard,
    lansiaList,
    statusItems,

    loadDashboard,
} = usePetugasDashboard();

const progress = computed(() => {
    const total =
        dashboard.value.pending +
        dashboard.value.diproses +
        dashboard.value.disalurkan;

    if (total === 0) {
        return 0;
    }

    return Math.round((dashboard.value.disalurkan / total) * 100);
});

const tugasHariIni = [
    {
        label: "Input Penilaian Lansia",
        sub: "Lengkapi data penilaian yang belum tersedia",
        link: "/penilaian",
        done: false,
    },
    {
        label: "Periksa Prioritas Bantuan",
        sub: "Pastikan ranking bantuan sudah diperbarui",
        link: "/hasil",
        done: false,
    },
    {
        label: "Monitoring Penyaluran",
        sub: "Lihat status distribusi bantuan",
        link: "/petugas/monitoring",
        done: false,
    },
];

onMounted(async () => {
    await loadDashboard();
});
</script>

<style scoped>
.tugas-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--warm-white);
    transition: all 0.15s;
}
.tugas-item.done {
    opacity: 0.55;
    background: var(--cream);
}
.tugas-check {
    flex-shrink: 0;
}
.tugas-info {
    flex: 1;
    min-width: 0;
}
.tugas-label {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--navy);
}
.tugas-sub {
    font-size: 12px;
    color: var(--muted);
    margin-top: 1px;
}
</style>
