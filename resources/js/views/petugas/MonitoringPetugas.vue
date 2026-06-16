<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Monitoring Saya</h2>
                <p class="text-muted mb-0">
                    Pantau status distribusi bantuan yang menjadi tanggung jawab
                    Anda
                </p>
            </div>
        </div>

        <!-- METRICS -->
        <div class="row-metrics mb-4">
            <div class="metric-card accent-red">
                <div class="metric-top"><AlertCircle :size="20" /></div>
                <div class="metric-val">
                    {{ metrics.pending }}
                </div>
                <div class="metric-lbl">Belum Disalurkan</div>
            </div>
            <div class="metric-card accent-gold">
                <div class="metric-top"><Clock :size="20" /></div>
                <div class="metric-val">
                    {{ metrics.diproses }}
                </div>
                <div class="metric-lbl">Sedang Diproses</div>
            </div>
            <div class="metric-card accent-green">
                <div class="metric-top"><CheckCircle :size="20" /></div>
                <div class="metric-val">
                    {{ metrics.disalurkan }}
                </div>
                <div class="metric-lbl">Sudah Disalurkan</div>
            </div>
        </div>

        <!-- PROGRESS -->
        <div class="dashboard-preview mb-4">
            <div class="dss-flex-between mb-2">
                <h6 class="section-title mb-0">Progress Distribusi Anda</h6>
                <span class="fw-bold text-muted"> {{ progress }}% </span>
            </div>
            <div class="progress-bar-wrap">
                <div
                    class="progress-bar-fill"
                    :style="{ width: `${progress}%` }"
                ></div>
            </div>
            <p class="text-muted small mt-2">
                {{ metrics.disalurkan }} dari
                {{ monitorData.length }}
                lansia telah menerima bantuan
            </p>
        </div>

        <!-- TABLE -->
        <div class="dashboard-preview">
            <div class="dss-flex-between mb-3">
                <h6 class="section-title mb-0">
                    Status Lansia yang Saya Tangani
                </h6>
                <span class="text-muted small">Update terbaru</span>
            </div>
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama Lansia</th>
                        <th>Prioritas</th>
                        <th>Jenis Bantuan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in monitorData" :key="item.nama">
                        <td class="fw-semibold">{{ item.nama }}</td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="item.prioClass"
                                >{{ item.prioritas }}</span
                            >
                        </td>
                        <td class="text-muted">{{ item.jenis }}</td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="item.statusClass"
                                >{{ item.status }}</span
                            >
                        </td>
                        <td class="text-muted small">
                            {{ item.catatan || "—" }}
                        </td>
                        <td class="text-muted small">{{ item.tanggal }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alert-light-box mt-4">
            <strong>Catatan:</strong> Segera perbarui status lansia yang sudah
            menerima bantuan agar admin dapat memantau progress distribusi
            secara akurat. Gunakan halaman
            <router-link
                to="/petugas/bantuan"
                style="color: var(--terracotta); font-weight: 600"
                >Penyaluran Bantuan</router-link
            >
            untuk memperbarui status.
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";

import { AlertCircle, Clock, CheckCircle } from "lucide-vue-next";

import { useMonitoringPetugas } from "../../composables/useMonitoringPetugas";

const {
    loading,
    error,

    monitorData,
    metrics,
    progress,

    loadMonitoring,
} = useMonitoringPetugas();

onMounted(async () => {
    await loadMonitoring();
});
</script>
