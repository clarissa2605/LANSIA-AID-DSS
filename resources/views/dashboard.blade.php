@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Dashboard</h2>
            <p class="text-muted mb-0">Ringkasan Sistem Pendukung Keputusan Lansia</p>
        </div>

        <a href="/lansia" class="btn btn-dark">
            + Tambah Data Lansia
        </a>
    </div>

    <!-- METRIC CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="metric-card accent-red">
                <div class="metric-top">
                    <i data-lucide="alert-circle"></i>
                    <span class="metric-badge badge-red">Kritis</span>
                </div>
                <div class="metric-val">1</div>
                <div class="metric-lbl">Prioritas Tinggi</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card accent-gold">
                <div class="metric-top">
                    <i data-lucide="alert-triangle"></i>
                    <span class="metric-badge badge-gold">Sedang</span>
                </div>
                <div class="metric-val">3</div>
                <div class="metric-lbl">Prioritas Sedang</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card accent-green">
                <div class="metric-top">
                    <i data-lucide="check-circle"></i>
                    <span class="metric-badge badge-green">Stabil</span>
                </div>
                <div class="metric-val">1</div>
                <div class="metric-lbl">Prioritas Rendah</div>
            </div>
        </div>

    </div>

    <!-- MAIN CONTENT -->
    <div class="row g-4 align-items-stretch">

        <!-- LEFT -->
        <div class="col-lg-8 d-flex">
            <div class="dashboard-preview flex-fill">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="section-title mb-0">Daftar Prioritas Tertinggi</h6>
                    <span class="text-muted small">Update hari ini</span>
                </div>

                <div class="priority-list">

                    @foreach ([
                        ['#1','Siti Rahayu','76 th · Sendiri · Wenang','0.78','fill-red'],
                        ['#2','Martha Kalalo','79 th · Sendiri · Malalayang','0.72','fill-gold'],
                        ['#3','Johan Runtuwene','74 th · Sendiri · Wenang','0.65','fill-green'],
                        ['#4','Yuliana Wenas','75 th · Sendiri · Mapanget','0.60','fill-muted'],
                        ['#5','Hendrik Mandagi','81 th · Bersama keluarga · Tuminting','0.55','fill-muted'],
                    ] as $item)

                    <div class="priority-item">
                        <div class="priority-rank">{{ $item[0] }}</div>

                        <div class="priority-info">
                            <div class="priority-name">{{ $item[1] }}</div>
                            <div class="priority-sub">{{ $item[2] }}</div>
                        </div>

                        <div class="priority-score">
                            <div class="score-val">{{ $item[3] }}</div>
                            <div class="score-bar">
                                <div class="score-fill {{ $item[4] }}"
                                     style="width: {{ $item[3] * 100 }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-4 d-flex flex-column gap-4">

            <!-- STATS -->
            <div class="dashboard-preview">
                <h6 class="section-title mb-3">Statistik Sistem</h6>

                <div class="stat-row">
                    <span>Total Lansia</span>
                    <strong>5</strong>
                </div>

                <div class="stat-row">
                    <span>Sudah Dinilai</span>
                    <strong>5</strong>
                </div>

                <div class="stat-row">
                    <span>Belum Dinilai</span>
                    <strong>0</strong>
                </div>

                <hr>

                <div class="stat-row">
                    <span>Bantuan Disalurkan</span>
                    <strong>1</strong>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="dashboard-preview">

                <h6 class="section-title mb-3">Aksi Cepat</h6>

                <div class="d-grid gap-2">

                    <a href="/lansia" class="btn btn-outline-dark">
                        + Tambah Data Lansia
                    </a>

                    <a href="/penilaian" class="btn btn-outline-dark">
                        Input Penilaian
                    </a>

                    <a href="/perhitungan" class="btn btn-dark">
                        Proses Perhitungan DSS
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
