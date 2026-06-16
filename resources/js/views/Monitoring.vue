<template>
<div class="container-fluid">

  <!-- HEADER -->
  <div class="dss-flex-between mb-4">
    <div>
      <h2 class="page-title">Monitoring Bantuan</h2>
      <p class="text-muted mb-0">Pemantauan distribusi bantuan lansia</p>
    </div>
  </div>

  <!-- METRIC CARDS -->
  <div class="row-metrics mb-4">
    <div class="metric-card accent-red">
      <div class="metric-top"><AlertCircle :size="20" /></div>
      <div class="metric-val">{{ pendingCount }}</div>
      <div class="metric-lbl">Belum Disalurkan</div>
    </div>
    <div class="metric-card accent-gold">
      <div class="metric-top"><Clock :size="20" /></div>
      <div class="metric-val">{{ diprosesCount }}</div>
      <div class="metric-lbl">Sedang Diproses</div>
    </div>
    <div class="metric-card accent-green">
      <div class="metric-top"><CheckCircle :size="20" /></div>
      <div class="metric-val">{{ disalurkanCount }}</div>
      <div class="metric-lbl">Sudah Disalurkan</div>
    </div>
  </div>

  <!-- PROGRESS -->
  <div class="dashboard-preview mb-4">
    <div class="dss-flex-between mb-2">
      <h6 class="section-title mb-0">Progress Distribusi Bantuan</h6>
      <span class="fw-bold text-muted">{{ progressPercent }}%</span>
    </div>
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" :style="`width:${progressPercent}%`"></div>
    </div>
    <p class="text-muted small mt-2">{{ disalurkanCount }} dari {{ totalCount }} lansia telah menerima bantuan</p>
  </div>

  <!-- TABLE -->
  <div class="dashboard-preview">
    <div class="dss-flex-between mb-3">
      <h6 class="section-title mb-0">Status Distribusi Per Lansia</h6>
      <span class="text-muted small">Update terbaru</span>
    </div>
    <div style="overflow-x:auto">
      <table class="app-table">
        <thead>
          <tr>
            <th>Nama Lansia</th>
            <th>Jenis Bantuan</th>
            <th>Urgensi</th>
            <th>Status</th>
            <th>Tanggal Pengajuan</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in monitorData" :key="item.id">
            <td class="fw-semibold">{{ item.nama }}</td>
            <td class="text-muted">{{ item.jenis }}</td>
            <td class="text-muted">{{ item.urgensi }}</td>
            <td><span class="badge rounded-pill px-3 py-2" :class="item.statusClass">{{ item.status }}</span></td>
            <td class="text-muted small">{{ item.tanggal_pengajuan }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- CATATAN -->
  <div class="alert-light-box mt-4">
    <strong>Catatan:</strong> Data monitoring diperbarui setiap kali status penyaluran diubah.
    Pastikan petugas mengkonfirmasi setiap penyaluran bantuan yang telah dilakukan.
  </div>

</div>
</template>

<script setup>
import { AlertCircle, Clock, CheckCircle } from 'lucide-vue-next';
import { computed, onMounted } from 'vue';
import { usePengajuanBantuan } from '../composables/usePengajuanBantuan.js';

const { pengajuan, loadPengajuan } = usePengajuanBantuan();

const monitorData = computed(() =>
  pengajuan.value.map((item) => ({
    id: item.id,
    nama: item.nama,
    jenis: item.jenis,
    urgensi: item.urgensi,
    status: formatStatus(item.status),
    statusClass: getStatusClass(item.status),
    tanggal_pengajuan: item.tanggal_pengajuan,
  })),
);

const pendingCount = computed(() =>
  pengajuan.value.filter((item) => item.status === 'pending').length,
);

const diprosesCount = computed(() =>
  pengajuan.value.filter((item) => item.status === 'diproses').length,
);

const disalurkanCount = computed(() =>
  pengajuan.value.filter((item) => item.status === 'disalurkan').length,
);

const totalCount = computed(() => pengajuan.value.length);

const progressPercent = computed(() => {
  if (!totalCount.value) {
    return 0;
  }
  return Math.round((disalurkanCount.value / totalCount.value) * 100);
});

onMounted(() => {
  loadPengajuan();
});

function formatStatus(value) {
  return {
    pending: 'Belum Disalurkan',
    diproses: 'Diproses',
    disalurkan: 'Disalurkan',
  }[value] ?? value;
}

function getStatusClass(status) {
  if (status === 'disalurkan') return 'bg-success';
  if (status === 'diproses') return 'bg-warning text-dark';
  return 'bg-secondary';
}
</script>
