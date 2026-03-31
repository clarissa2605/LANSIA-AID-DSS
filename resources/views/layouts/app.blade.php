<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Pendukung Keputusan Lansia</title>
  <link href="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css)" rel="stylesheet">
  <link href="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css)" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container-fluid">
      <button class="btn btn-outline-light me-3" id="menu-toggle"><i class="bi bi-list"></i></button>
      <a class="navbar-brand fw-bold" href="#">SPK Bantuan Lansia</a>
      <div class="dropdown ms-auto">
        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle"></i> Admin
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Profil</a></li>
          <li><a class="dropdown-item" href="#">Keluar</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="bg-white border-end" style="width: 240px; min-height: 100vh;">
      <ul class="list-group list-group-flush mt-3">
        <li><a href="/dashboard" class="list-group-item list-group-item-action"><i class="bi bi-house-door"></i> Dashboard</a></li>
        <li><a href="/data-lansia" class="list-group-item list-group-item-action"><i class="bi bi-person-lines-fill"></i> Data Lansia</a></li>
        <li><a href="/kriteria" class="list-group-item list-group-item-action"><i class="bi bi-bar-chart"></i> Kriteria</a></li>
        <li><a href="/penilaian" class="list-group-item list-group-item-action"><i class="bi bi-journal-text"></i> Penilaian</a></li>
        <li><a href="/perhitungan" class="list-group-item list-group-item-action"><i class="bi bi-calculator"></i> Perhitungan</a></li>
        <li><a href="/hasil-prioritas" class="list-group-item list-group-item-action"><i class="bi bi-trophy"></i> Hasil Prioritas</a></li>
        <li><a href="/penyaluran" class="list-group-item list-group-item-action"><i class="bi bi-gift"></i> Penyaluran Bantuan</a></li>
        <li><a href="/monitoring" class="list-group-item list-group-item-action"><i class="bi bi-clipboard-data"></i> Monitoring</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4" id="page-content">
      @yield('content')
    </div>
  </div>

  <script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js)"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('menu-toggle');
    toggle.addEventListener('click', () => {
      sidebar.classList.toggle('d-none');
    });
  </script>
</body>
</html>
