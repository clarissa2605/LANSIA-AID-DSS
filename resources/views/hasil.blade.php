@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Hasil Prioritas</h2>
            <p class="text-muted mb-0">Urutan lansia berdasarkan kebutuhan bantuan</p>
        </div>

        <button class="btn btn-dark">
            Export PDF
        </button>
    </div>

    <!-- TOP 3 -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="metric-card accent-red">
                <div class="metric-val">#1</div>
                <div class="metric-lbl">Siti Rahayu</div>
                <div class="metric-sub">0.78 • Prioritas Utama</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card accent-gold">
                <div class="metric-val">#2</div>
                <div class="metric-lbl">Martha Kalalo</div>
                <div class="metric-sub">0.72 • Diprioritaskan</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card accent-blue">
                <div class="metric-val">#3</div>
                <div class="metric-lbl">Johan Runtuwene</div>
                <div class="metric-sub">0.65 • Cukup</div>
            </div>
        </div>

    </div>

    <!-- FULL RANKING -->
    <div class="dashboard-preview">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="section-title mb-0">Ranking Lengkap</h6>
            <span class="text-muted small">Update terbaru</span>
        </div>

        <div class="priority-list">

            @foreach ([
                ['#1','Siti Rahayu','76 th · Sendiri · Wenang','0.78','fill-red','Prioritas Utama','bg-danger'],
                ['#2','Martha Kalalo','79 th · Sendiri · Malalayang','0.72','fill-gold','Diprioritaskan','bg-warning text-dark'],
                ['#3','Johan Runtuwene','74 th · Sendiri · Wenang','0.65','fill-blue','Cukup','bg-primary'],
                ['#4','Yuliana Wenas','75 th · Sendiri · Mapanget','0.60','fill-blue','Cukup','bg-primary'],
                ['#5','Hendrik Mandagi','81 th · Bersama keluarga · Tuminting','0.55','fill-muted','Rendah','bg-secondary'],
            ] as $item)

            <div class="priority-item">

                <!-- RANK -->
                <div class="priority-rank">{{ $item[0] }}</div>

                <!-- INFO -->
                <div class="priority-info">
                    <div class="priority-name">{{ $item[1] }}</div>
                    <div class="priority-sub">{{ $item[2] }}</div>
                </div>

                <!-- SCORE -->
                <div class="priority-score">
                    <div class="score-val">{{ $item[3] }}</div>
                    <div class="score-bar">
                        <div class="score-fill {{ $item[4] }}"
                             style="width: {{ $item[3] * 100 }}%">
                        </div>
                    </div>
                </div>

                <!-- STATUS -->
                <div>
                    <span class="badge rounded-pill px-3 py-2 {{ $item[6] }}">
                        {{ $item[5] }}
                    </span>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

@endsection
