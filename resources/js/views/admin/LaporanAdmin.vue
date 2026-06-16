<template>
<div class="container-fluid">

  <!-- HEADER -->
  <div class="dss-flex-between mb-4">
    <div>
      <h2 class="page-title">Laporan & Monitoring</h2>
      <p class="text-muted mb-0">Ringkasan seluruh aktivitas distribusi bantuan lansia</p>
    </div>
    <button class="btn btn-dark">Export PDF</button>
  </div>

  <!-- METRICS -->
  <div class="row-metrics mb-4">
    <div class="metric-card accent-red">
      <div class="metric-top"><AlertCircle :size="20" /></div>
      <div class="metric-val">3</div>
      <div class="metric-lbl">Belum Disalurkan</div>
    </div>
    <div class="metric-card accent-gold">
      <div class="metric-top"><Clock :size="20" /></div>
      <div class="metric-val">1</div>
      <div class="metric-lbl">Sedang Diproses</div>
    </div>
    <div class="metric-card accent-green">
      <div class="metric-top"><CheckCircle :size="20" /></div>
      <div class="metric-val">1</div>
      <div class="metric-lbl">Sudah Disalurkan</div>
    </div>
  </div>

  <!-- PROGRESS -->
  <div class="dashboard-preview mb-4">
    <div class="dss-flex-between mb-2">
      <h6 class="section-title mb-0">Progress Distribusi Keseluruhan</h6>
      <span class="fw-bold text-muted">20%</span>
    </div>
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" style="width:20%"></div>
    </div>
    <p class="text-muted small mt-2">1 dari 5 lansia telah menerima bantuan</p>
  </div>

  <!-- TABLE REKAPITULASI -->
  <div class="dashboard-preview mb-4">
    <div class="dss-flex-between mb-3">
      <h6 class="section-title mb-0">Rekapitulasi Per Petugas</h6>
    </div>
    <table class="app-table">
      <thead>
        <tr>
          <th>Nama Petugas</th>
          <th>Lansia Ditangani</th>
          <th>Sudah Disalurkan</th>
          <th>Belum Disalurkan</th>
          <th>Progress</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in rekapPetugas" :key="p.nama">
          <td class="fw-semibold">{{ p.nama }}</td>
          <td style="text-align:center">{{ p.total }}</td>
          <td style="text-align:center">
            <span class="badge rounded-pill px-3 py-2 bg-success">{{ p.disalurkan }}</span>
          </td>
          <td style="text-align:center">
            <span class="badge rounded-pill px-3 py-2 bg-secondary">{{ p.belum }}</span>
          </td>
          <td style="min-width:140px">
            <div class="dss-flex-between" style="margin-bottom:4px">
              <span class="small text-muted">{{ Math.round(p.disalurkan / p.total * 100) }}%</span>
            </div>
            <div class="progress-bar-wrap">
              <div class="progress-bar-fill"
                :style="{width: (p.disalurkan / p.total * 100) + '%'}"></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- TABLE DETAIL STATUS -->
  <div class="dashboard-preview">
    <div class="dss-flex-between mb-3">
      <h6 class="section-title mb-0">Detail Status Per Lansia</h6>
      <span class="text-muted small">Semua petugas</span>
    </div>
    <table class="app-table">
      <thead>
        <tr>
          <th>Nama Lansia</th>
          <th>Prioritas</th>
          <th>Petugas</th>
          <th>Jenis Bantuan</th>
          <th>Status</th>
          <th>Tanggal Update</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in detailData" :key="item.nama">
          <td class="fw-semibold">{{ item.nama }}</td>
          <td><span class="badge rounded-pill px-3 py-2" :class="item.prioClass">{{ item.prioritas }}</span></td>
          <td class="text-muted">{{ item.petugas }}</td>
          <td class="text-muted">{{ item.jenis }}</td>
          <td><span class="badge rounded-pill px-3 py-2" :class="item.statusClass">{{ item.status }}</span></td>
          <td class="text-muted small">{{ item.tanggal }}</td>
        </tr>
      </tbody>
    </table>
  </div>

</div>
</template>

<script setup>
import { AlertCircle, Clock, CheckCircle } from 'lucide-vue-next';

const rekapPetugas = [
  { nama: 'Budi Santoso', total: 3, disalurkan: 1, belum: 2 },
  { nama: 'Citra Dewi',   total: 2, disalurkan: 0, belum: 2 },
];

const detailData = [
  { nama:'Siti Rahayu',     prioritas:'Prioritas Utama', prioClass:'bg-danger',            petugas:'Budi Santoso', jenis:'Bantuan Kesehatan', status:'Diproses',        statusClass:'bg-warning text-dark', tanggal:'01 Apr 2026' },
  { nama:'Martha Kalalo',   prioritas:'Diprioritaskan',  prioClass:'bg-warning text-dark', petugas:'Budi Santoso', jenis:'Bantuan Sosial',    status:'Disalurkan',       statusClass:'bg-success',           tanggal:'30 Mar 2026' },
  { nama:'Johan Runtuwene', prioritas:'Cukup',           prioClass:'bg-primary',           petugas:'Budi Santoso', jenis:'Bantuan Sosial',    status:'Belum Disalurkan', statusClass:'bg-secondary',         tanggal:'—' },
  { nama:'Yuliana Wenas',   prioritas:'Cukup',           prioClass:'bg-primary',           petugas:'Citra Dewi',   jenis:'Bantuan Sosial',    status:'Belum Disalurkan', statusClass:'bg-secondary',         tanggal:'—' },
  { nama:'Hendrik Mandagi', prioritas:'Rendah',          prioClass:'bg-secondary',         petugas:'Citra Dewi',   jenis:'Bantuan Sosial',    status:'Belum Disalurkan', statusClass:'bg-secondary',         tanggal:'—' },
];
</script>
