@extends('layouts.app')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-house-door"></i> Dashboard</h4>

<div class="row g-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bi bi-person-lines-fill display-5 text-primary"></i>
        <h6 class="mt-3">Total Lansia</h6>
        <h4>124</h4>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bi bi-trophy display-5 text-success"></i>
        <h6 class="mt-3">Bantuan Disalurkan</h6>
        <h4>45</h4>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bi bi-journal-text display-5 text-warning"></i>
        <h6 class="mt-3">Penilaian Aktif</h6>
        <h4>8</h4>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bi bi-clipboard-data display-5 text-danger"></i>
        <h6 class="mt-3">Monitoring</h6>
        <h4>12</h4>
      </div>
    </div>
  </div>
</div>

<div class="mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      Statistik Bantuan
    </div>
    <div class="card-body">
      <canvas id="chartBantuan"></canvas>
    </div>
  </div>
</div>

<script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/chart.js)"></script>
<script>
  const ctx = document.getElementById('chartBantuan');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Januari', 'Februari', 'Maret', 'April'],
      datasets: [{
        label: 'Jumlah Bantuan',
        data: [10, 15, 8, 20],
        backgroundColor: '#0d6efd'
      }]
    }
  });
</script>
@endsection
