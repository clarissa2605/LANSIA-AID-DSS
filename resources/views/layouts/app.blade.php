<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SiLANSIA DSS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?
family=Playfair+Display:wght@400;600;700&
family=DM+Sans:wght@300;400;500;600&
family=DM+Mono:wght@400;500&
display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg px-4 py-3">

   <div class="nav-logo">
    <div class="logo-text brand">
        Si<span>LANSIA</span>
    </div>
</div>
</div>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        ☰
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav ms-auto gap-3">

    <li class="nav-item">
        <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i> Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/lansia') }}" class="nav-link {{ request()->is('lansia') ? 'active' : '' }}">
            <i data-lucide="users"></i> Data Lansia
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/kriteria') }}" class="nav-link {{ request()->is('kriteria') ? 'active' : '' }}">
            <i data-lucide="sliders-horizontal"></i> Kriteria
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/penilaian') }}" class="nav-link {{ request()->is('penilaian') ? 'active' : '' }}">
            <i data-lucide="edit-3"></i> Penilaian
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/perhitungan') }}" class="nav-link {{ request()->is('perhitungan') ? 'active' : '' }}">
            <i data-lucide="calculator"></i> Perhitungan
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/hasil') }}" class="nav-link {{ request()->is('hasil') ? 'active' : '' }}">
            <i data-lucide="bar-chart-3"></i> Hasil Prioritas
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/bantuan') }}" class="nav-link {{ request()->is('bantuan') ? 'active' : '' }}">
            <i data-lucide="package"></i> Penyaluran
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('/monitoring') }}" class="nav-link {{ request()->is('monitoring') ? 'active' : '' }}">
            <i data-lucide="activity"></i> Monitoring
        </a>
    </li>

</ul>
    </div>
</nav>

<!-- CONTENT -->
<div class="container-fluid px-5 main-content">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
