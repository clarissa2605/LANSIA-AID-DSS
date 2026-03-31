@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="page-title">Monitoring Bantuan</h2>
        <p class="text-muted mb-0">Pemantauan distribusi bantuan lansia</p>
    </div>

    <!-- METRICS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="metric-card accent-red">
                <div class="metric-val">3</div>
                <div class="metric-lbl">Belum Disalurkan</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card accent-gold">
                <div class="metric-val">1</div>
                <div class="metric-lbl">Diproses</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card accent-green">
                <div class="metric-val">1</div>
                <div class="metric-lbl">Disalurkan</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-val">5</div>
                <div class="metric-lbl">Total Lansia</div>
            </div>
        </div>

    </div>

    <!-- PROGRESS -->
    <div class="dashboard-preview mb-4">

        <h6 class="section-title mb-3">Progress Distribusi</h6>

        <div class="mb-2 d-flex justify-content-between">
            <span>Progress Total</span>
            <strong>20%</strong>
        </div>

        <div class="progress" style="height: 10px; border-radius: 10px;">
            <div class="progress-bar bg-success" style="width:20%"></div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="dashboard-preview">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="section-title mb-0">Status Lansia</h6>
            <span class="text-muted small">Update terbaru</span>
        </div>

        <table class="app-table align-middle">

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Prioritas</th>
                    <th>Status Bantuan</th>
                    <th>Terakhir Update</th>
                </tr>
            </thead>

            <tbody>

                <!-- 1 -->
                <tr>
                    <td class="fw-semibold">Siti Rahayu</td>
                    <td><span class="badge bg-danger rounded-pill px-3 py-2">Prioritas Utama</span></td>
                    <td><span class="badge bg-warning text-dark rounded-pill px-3 py-2">Diproses</span></td>
                    <td class="text-muted small">01 Apr 2026</td>
                </tr>

                <!-- 2 -->
                <tr>
                    <td class="fw-semibold">Martha Kalalo</td>
                    <td><span class="badge bg-warning text-dark rounded-pill px-3 py-2">Diprioritaskan</span></td>
                    <td><span class="badge bg-success rounded-pill px-3 py-2">Disalurkan</span></td>
                    <td class="text-muted small">30 Mar 2026</td>
                </tr>

                <!-- 3 -->
                <tr>
                    <td class="fw-semibold">Johan Runtuwene</td>
                    <td><span class="badge bg-primary rounded-pill px-3 py-2">Cukup</span></td>
                    <td><span class="badge bg-secondary rounded-pill px-3 py-2">Belum Disalurkan</span></td>
                    <td class="text-muted small">-</td>
                </tr>

                <!-- 4 -->
                <tr>
                    <td class="fw-semibold">Yuliana Wenas</td>
                    <td><span class="badge bg-primary rounded-pill px-3 py-2">Cukup</span></td>
                    <td><span class="badge bg-secondary rounded-pill px-3 py-2">Belum Disalurkan</span></td>
                    <td class="text-muted small">-</td>
                </tr>

                <!-- 5 -->
                <tr>
                    <td class="fw-semibold">Hendrik Mandagi</td>
                    <td><span class="badge bg-secondary rounded-pill px-3 py-2">Rendah</span></td>
                    <td><span class="badge bg-secondary rounded-pill px-3 py-2">Belum Disalurkan</span></td>
                    <td class="text-muted small">-</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
