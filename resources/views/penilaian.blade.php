@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Penilaian Lansia</h2>
            <p class="text-muted mb-0">Isi penilaian sesuai kondisi lansia</p>
        </div>

        <button class="btn btn-dark">
            Simpan Penilaian
        </button>
    </div>

    <!-- PETUNJUK SINGKAT -->
    <div class="alert alert-light border mb-4">
        <strong>Petunjuk:</strong><br>
        Berikan nilai dari <strong>1 sampai 5</strong> sesuai kondisi lansia.
        Semakin tinggi nilai, semakin diprioritaskan untuk menerima bantuan.
    </div>

    <!-- PILIH LANSIA -->
    <div class="dashboard-preview mb-4">
        <label class="form-label mb-2">Pilih Lansia</label>
        <select class="form-control">
            <option>-- Pilih Lansia --</option>
            <option>Siti Rahayu</option>
            <option>Johan Runtuwene</option>
            <option>Martha Kalalo</option>
            <option>Hendrik Mandagi</option>
            <option>Yuliana Wenas</option>
        </select>
    </div>

    <!-- INPUT NILAI -->
    <div class="dashboard-preview">

        <h6 class="mb-3 fw-semibold">Isi Penilaian</h6>

        <div class="table-responsive">
            <table class="table align-middle text-center">

                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Kesehatan</td>
                        <td>
                            <input type="number" min="1" max="5" class="form-control text-center">
                        </td>
                        <td class="text-muted">1 = sehat, 5 = sangat sakit</td>
                    </tr>

                    <tr>
                        <td>Ekonomi</td>
                        <td>
                            <input type="number" min="1" max="5" class="form-control text-center">
                        </td>
                        <td class="text-muted">1 = mampu, 5 = tidak mampu</td>
                    </tr>

                    <tr>
                        <td>Status Tinggal</td>
                        <td>
                            <input type="number" min="1" max="5" class="form-control text-center">
                        </td>
                        <td class="text-muted">1 = dengan keluarga, 5 = sendiri</td>
                    </tr>

                    <tr>
                        <td>Usia</td>
                        <td>
                            <input type="number" min="1" max="5" class="form-control text-center">
                        </td>
                        <td class="text-muted">Semakin tua, nilai semakin tinggi</td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection
