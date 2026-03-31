@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Kriteria Penilaian</h3>
            <p class="text-muted mb-0">Pengaturan bobot kriteria (AHP)</p>
        </div>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalKriteria">
            + Tambah Kriteria
        </button>
    </div>

    <!-- PENJELASAN -->
    <div class="dashboard-preview mb-4">
        <h6 class="fw-semibold mb-2">Penjelasan Kriteria</h6>
        <p class="text-muted mb-3">
            Kriteria berikut digunakan untuk menentukan prioritas pemberian bantuan kepada lansia
            menggunakan metode AHP dan TOPSIS.
        </p>

        <ul class="mb-0 text-muted">
            <li><strong>Kesehatan</strong> → Semakin buruk kondisi kesehatan, semakin diprioritaskan (Cost)</li>
            <li><strong>Ekonomi</strong> → Semakin rendah kondisi ekonomi, semakin diprioritaskan (Cost)</li>
            <li><strong>Status Tinggal</strong> → Lansia yang tinggal sendiri lebih diprioritaskan (Cost)</li>
            <li><strong>Usia</strong> → Semakin tua usia, semakin diprioritaskan (Benefit)</li>
        </ul>
    </div>

    <!-- TABLE KRITERIA -->
    <div class="dashboard-preview mb-4">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Kriteria</th>
                    <th>Deskripsi</th>
                    <th>Jenis</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Kesehatan</td>
                    <td>Kondisi kesehatan lansia (sakit, kronis, sehat)</td>
                    <td><span class="badge bg-danger">Cost</span></td>
                    <td>0.40</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </td>
                </tr>

                <tr>
                    <td>Ekonomi</td>
                    <td>Kondisi finansial lansia (tidak ada penghasilan, rendah, cukup)</td>
                    <td><span class="badge bg-warning text-dark">Cost</span></td>
                    <td>0.30</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </td>
                </tr>

                <tr>
                    <td>Status Tinggal</td>
                    <td>Apakah lansia tinggal sendiri atau bersama keluarga</td>
                    <td><span class="badge bg-danger">Cost</span></td>
                    <td>0.20</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </td>
                </tr>

                <tr>
                    <td>Usia</td>
                    <td>Umur lansia (semakin tua semakin prioritas)</td>
                    <td><span class="badge bg-success">Benefit</span></td>
                    <td>0.10</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- AHP INFO -->
    <div class="alert alert-light border mb-3">
        <strong>AHP (Analytical Hierarchy Process)</strong><br>
        Digunakan untuk menentukan bobot setiap kriteria melalui perbandingan berpasangan.
        Nilai menunjukkan tingkat kepentingan antar kriteria.
    </div>

    <!-- SKALA AHP -->
    <div class="dashboard-preview mb-4">
        <h6 class="fw-semibold mb-2">Skala Perbandingan AHP</h6>

        <table class="table table-sm">
            <tr><td>1</td><td>Sama penting</td></tr>
            <tr><td>3</td><td>Sedikit lebih penting</td></tr>
            <tr><td>5</td><td>Lebih penting</td></tr>
            <tr><td>7</td><td>Sangat penting</td></tr>
            <tr><td>9</td><td>Mutlak lebih penting</td></tr>
        </table>
    </div>

    <!-- AHP MATRIX -->
    <div class="dashboard-preview">

        <h6 class="mb-3 fw-semibold">Matriks Perbandingan (AHP)</h6>

        <div class="table-responsive">
            <table class="app-table">

                <thead>
                    <tr>
                        <th></th>
                        <th>Kesehatan</th>
                        <th>Ekonomi</th>
                        <th>Status</th>
                        <th>Usia</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <th>Kesehatan</th>
                        <td>1</td>
                        <td><input type="number" class="form-control text-center" value="2"></td>
                        <td><input type="number" class="form-control text-center" value="3"></td>
                        <td><input type="number" class="form-control text-center" value="4"></td>
                    </tr>

                    <tr>
                        <th>Ekonomi</th>
                        <td><input type="number" class="form-control text-center" value="0.5"></td>
                        <td>1</td>
                        <td><input type="number" class="form-control text-center" value="2"></td>
                        <td><input type="number" class="form-control text-center" value="3"></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td><input type="number" class="form-control text-center" value="0.33"></td>
                        <td><input type="number" class="form-control text-center" value="0.5"></td>
                        <td>1</td>
                        <td><input type="number" class="form-control text-center" value="2"></td>
                    </tr>

                    <tr>
                        <th>Usia</th>
                        <td><input type="number" class="form-control text-center" value="0.25"></td>
                        <td><input type="number" class="form-control text-center" value="0.33"></td>
                        <td><input type="number" class="form-control text-center" value="0.5"></td>
                        <td>1</td>
                    </tr>

                </tbody>

            </table>
        </div>

        <div class="mt-3">
            <button class="btn btn-dark">Hitung Bobot AHP</button>
        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalKriteria">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Kriteria</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama Kriteria</label>
                    <input type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select class="form-control">
                        <option>Benefit</option>
                        <option>Cost</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-dark">Simpan</button>
            </div>

        </div>
    </div>
</div>

@endsection
