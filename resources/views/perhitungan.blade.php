@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mb-4">
        <h2>Perhitungan Prioritas</h2>
        <p class="text-muted mb-0">Menentukan lansia yang diprioritaskan menerima bantuan</p>
    </div>

    <!-- PENJELASAN -->
    <div class="alert alert-light border mb-4">
        <strong>Penjelasan:</strong><br>
        Sistem akan mengolah nilai penilaian untuk menghasilkan urutan prioritas lansia
        berdasarkan tingkat kebutuhan bantuan.
    </div>

    <!-- MAIN ACTION -->
    <div class="dashboard-preview text-center mb-4">
        <h5 class="mb-2">Proses Perhitungan</h5>
        <p class="text-muted">Klik tombol untuk memproses data</p>

        <button class="btn btn-dark mt-2">
            Jalankan Perhitungan
        </button>
    </div>

    <!-- HASIL -->
    <div class="dashboard-preview mb-4">

        <h6 class="fw-semibold mb-3">Hasil Prioritas</h6>


        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Nama</th>
                    <th>Skor</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>Siti Rahayu</td>
                    <td>0.78</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 bg-danger">
                            Prioritas Utama
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Martha Kalalo</td>
                    <td>0.72</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark">
                            Diprioritaskan
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Johan Runtuwene</td>
                    <td>0.65</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 bg-primary">
                            Cukup
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>Yuliana Wenas</td>
                    <td>0.60</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 bg-primary">
                            Cukup
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>5</td>
                    <td>Hendrik Mandagi</td>
                    <td>0.55</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 bg-secondary">
                            Rendah
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- TOGGLE DETAIL -->
    <div class="mb-3">
        <button class="btn btn-outline-dark" onclick="toggleDetail()">
            Lihat Detail Perhitungan
        </button>
    </div>

    <!-- DETAIL -->
    <div id="detailSection" style="display:none;">

        <!-- STEP 1 -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Data Penilaian</h6>
            <p class="text-muted small">Nilai awal yang diberikan untuk setiap lansia</p>

            <table class="app-table">
                <thead>
                    <tr>
                        <th>Lansia</th>
                        <th>Kesehatan</th>
                        <th>Ekonomi</th>
                        <th>Status</th>
                        <th>Usia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Siti Rahayu</td><td>4</td><td>5</td><td>5</td><td>3</td></tr>
                    <tr><td>Johan Runtuwene</td><td>3</td><td>4</td><td>5</td><td>4</td></tr>
                    <tr><td>Martha Kalalo</td><td>5</td><td>5</td><td>4</td><td>2</td></tr>
                    <tr><td>Hendrik Mandagi</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
                    <tr><td>Yuliana Wenas</td><td>4</td><td>4</td><td>3</td><td>3</td></tr>
                </tbody>
            </table>
        </div>

        <!-- STEP 2 -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Penyesuaian Nilai</h6>
            <p class="text-muted small">Nilai disesuaikan agar bisa dibandingkan secara adil</p>

            <table class="app-table">
                <tbody>
                    <tr><td>Siti Rahayu</td><td>0.56</td><td>0.62</td><td>0.70</td><td>0.45</td></tr>
                    <tr><td>Johan Runtuwene</td><td>0.42</td><td>0.50</td><td>0.70</td><td>0.60</td></tr>
                    <tr><td>Martha Kalalo</td><td>0.70</td><td>0.62</td><td>0.56</td><td>0.30</td></tr>
                    <tr><td>Hendrik Mandagi</td><td>0.28</td><td>0.37</td><td>0.56</td><td>0.75</td></tr>
                    <tr><td>Yuliana Wenas</td><td>0.56</td><td>0.50</td><td>0.42</td><td>0.45</td></tr>
                </tbody>
            </table>
        </div>

        <!-- STEP 3 -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Perhitungan Bobot</h6>
            <p class="text-muted small">Nilai dikalikan dengan tingkat kepentingan kriteria</p>

            <table class="app-table">
                <tbody>
                    <tr><td>Siti Rahayu</td><td>0.22</td><td>0.18</td><td>0.14</td><td>0.04</td></tr>
                    <tr><td>Johan Runtuwene</td><td>0.17</td><td>0.15</td><td>0.14</td><td>0.06</td></tr>
                    <tr><td>Martha Kalalo</td><td>0.28</td><td>0.18</td><td>0.11</td><td>0.03</td></tr>
                    <tr><td>Hendrik Mandagi</td><td>0.11</td><td>0.11</td><td>0.11</td><td>0.07</td></tr>
                    <tr><td>Yuliana Wenas</td><td>0.22</td><td>0.15</td><td>0.08</td><td>0.04</td></tr>
                </tbody>
            </table>
        </div>

        <!-- STEP 4 -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Nilai Acuan</h6>
            <p class="text-muted small">Digunakan sebagai pembanding untuk menentukan hasil akhir</p>

            <div class="d-flex justify-content-between">
                <div>
                    <p class="fw-semibold mb-1">Nilai Tertinggi</p>
                    <p class="text-muted">[0.28, 0.18, 0.14, 0.07]</p>
                </div>

                <div>
                    <p class="fw-semibold mb-1">Nilai Terendah</p>
                    <p class="text-muted">[0.11, 0.11, 0.08, 0.03]</p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
function toggleDetail() {
    let el = document.getElementById("detailSection");
    el.style.display = el.style.display === "none" ? "block" : "none";
}
</script>

@endsection
