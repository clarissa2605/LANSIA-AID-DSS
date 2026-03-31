@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Data Lansia</h3>
            <p class="text-muted mb-0">Manajemen data penerima bantuan</p>
        </div>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalLansia">
            + Tambah Lansia
        </button>
    </div>

    <!-- SEARCH -->
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Cari nama lansia...">
    </div>

    <!-- TABLE -->
    <div class="dashboard-preview">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Umur</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Ekonomi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

    <tr>
        <td>Siti Rahayu</td>
        <td>76</td>
        <td>Wenang</td>
        <td><span class="badge bg-danger">Sendiri</span></td>
        <td><span class="badge bg-warning text-dark">Tidak Ada</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </td>
    </tr>

    <tr>
        <td>Johan Runtuwene</td>
        <td>74</td>
        <td>Wenang</td>
        <td><span class="badge bg-success">Keluarga</span></td>
        <td><span class="badge bg-warning text-dark">Di bawah UMR</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </td>
    </tr>

    <tr>
        <td>Martha Kalalo</td>
        <td>79</td>
        <td>Malalayang</td>
        <td><span class="badge bg-danger">Sendiri</span></td>
        <td><span class="badge bg-warning text-dark">Tidak Ada</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </td>
    </tr>

    <tr>
        <td>Hendrik Mandagi</td>
        <td>81</td>
        <td>Tuminting</td>
        <td><span class="badge bg-success">Keluarga</span></td>
        <td><span class="badge bg-warning text-dark">Di bawah UMR</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </td>
    </tr>

    <tr>
        <td>Yuliana Wenas</td>
        <td>75</td>
        <td>Mapanget</td>
        <td><span class="badge bg-danger">Sendiri</span></td>
        <td><span class="badge bg-warning text-dark">Di bawah UMR</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </td>
    </tr>

</tbody>
        </table>
    </div>

</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalLansia">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Lansia</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Umur</label>
                        <input type="number" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Tinggal</label>
                        <select class="form-control">
                            <option>Tinggal Sendiri</option>
                            <option>Bersama Keluarga</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kondisi Ekonomi</label>
                        <select class="form-control">
                            <option>Tidak Ada</option>
                            <option>Di bawah UMR</option>
                            <option>Di atas UMR</option>
                        </select>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-dark">Simpan</button>
            </div>

        </div>
    </div>
</div>

@endsection
