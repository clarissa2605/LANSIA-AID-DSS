<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Dashboard</h2>
                <p class="text-muted mb-0">
                    Ringkasan Sistem Pendukung Keputusan Lansia
                </p>
            </div>
            <router-link to="/lansia" class="btn btn-dark"
                >+ Tambah Data Lansia</router-link
            >
        </div>

        <!-- METRIC CARDS -->
        <div class="row-metrics mb-4">
            <div class="metric-card accent-red">
                <div class="metric-top">
                    <AlertCircle :size="20" />
                    <span class="metric-badge badge-red">Kritis</span>
                </div>
                <div class="metric-val">{{ priorityStats.tinggi }}</div>
                <div class="metric-lbl">Prioritas Tinggi</div>
            </div>
            <div class="metric-card accent-gold">
                <div class="metric-top">
                    <AlertTriangle :size="20" />
                    <span class="metric-badge badge-gold">Sedang</span>
                </div>
                <div class="metric-val">{{ priorityStats.sedang }}</div>
                <div class="metric-lbl">Prioritas Sedang</div>
            </div>
            <div class="metric-card accent-green">
                <div class="metric-top">
                    <CheckCircle :size="20" />
                    <span class="metric-badge badge-green">Stabil</span>
                </div>
                <div class="metric-val">{{ priorityStats.rendah }}</div>
                <div class="metric-lbl">Prioritas Rendah</div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="row-main">
            <!-- LEFT -->
            <div class="col-left">
                <div class="dashboard-preview">
                    <div class="dss-flex-between mb-3">
                        <h6 class="section-title mb-0">
                            Daftar Prioritas Tertinggi
                        </h6>
                        <span class="text-muted small">{{
                            loading ? "Memuat..." : "Update hari ini"
                        }}</span>
                    </div>

                    <div class="priority-list">
                        <div
                            v-for="item in topPriority"
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-right">
                <!-- STATS -->
                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Statistik Sistem</h6>
                    <div class="stat-row">
                        <span>Total Lansia</span
                        ><strong>{{ statistik.total_lansia }}</strong>
                    </div>
                    <div class="stat-row">
                        <span>Sudah Dinilai</span
                        ><strong>{{ statistik.sudah_dinilai }}</strong>
                    </div>
                    <div class="stat-row">
                        <span>Belum Dinilai</span
                        ><strong>{{ statistik.belum_dinilai }}</strong>
                    </div>
                    <hr style="border-color: var(--border); margin: 12px 0" />
                    <div class="stat-row">
                        <span>Bantuan Disalurkan</span
                        ><strong>{{ statistik.bantuan_disalurkan }}</strong>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="dashboard-preview">
                    <h6 class="section-title mb-3">Aksi Cepat</h6>
                    <div style="display: grid; gap: 8px">
                        <router-link to="/lansia" class="btn btn-outline-dark"
                            >+ Tambah Data Lansia</router-link
                        >
                        <router-link
                            to="/penilaian"
                            class="btn btn-outline-dark"
                            >Input Penilaian</router-link
                        >
                        <router-link to="/perhitungan" class="btn btn-dark"
                            >Proses Perhitungan DSS</router-link
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
```javascript
<script setup>
import { onMounted } from "vue";
import { AlertCircle, AlertTriangle, CheckCircle } from "lucide-vue-next";

import { useDashboard } from "../composables/useDashboard";

const { loading, error, statistik, topPriority, priorityStats, loadDashboard } =
    useDashboard();

onMounted(() => {
    loadDashboard();
});
</script>
```
