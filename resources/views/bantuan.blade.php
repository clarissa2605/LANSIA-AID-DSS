@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Penyaluran Bantuan</h2>
            <p class="text-muted mb-0">Distribusi bantuan berdasarkan hasil prioritas</p>
        </div>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Penyaluran
        </button>
    </div>

    <!-- TABLE -->
    <div class="dashboard-preview">

        <table class="app-table align-middle">
            <thead>
                <tr>
                    <th>Nama Lansia</th>
                    <th>Prioritas</th>
                    <th>Jenis Bantuan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="penyaluranTable">

                @foreach([
                    ['Siti Rahayu','Prioritas Utama','bg-danger','Bantuan Kesehatan','01 Apr 2026','Diproses','bg-warning text-dark'],
                    ['Martha Kalalo','Diprioritaskan','bg-warning text-dark','Bantuan Sosial','30 Mar 2026','Disalurkan','bg-success'],
                    ['Johan Runtuwene','Cukup','bg-primary','Bantuan Sosial','28 Mar 2026','Belum Disalurkan','bg-secondary'],
                    ['Yuliana Wenas','Cukup','bg-primary','Bantuan Sosial','27 Mar 2026','Belum Disalurkan','bg-secondary'],
                    ['Hendrik Mandagi','Rendah','bg-secondary','Bantuan Sosial','25 Mar 2026','Belum Disalurkan','bg-secondary'],
                ] as $item)

                <tr>
                    <td class="fw-semibold">{{ $item[0] }}</td>

                    <td>
                        <span class="badge rounded-pill px-3 py-2 {{ $item[2] }}">
                            {{ $item[1] }}
                        </span>
                    </td>

                    <td class="text-muted">{{ $item[3] }}</td>

                    <td class="text-muted small">{{ $item[4] }}</td>

                    <td>
                        <span class="badge rounded-pill px-3 py-2 {{ $item[6] }}">
                            {{ $item[5] }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex gap-2">

                            <button
                                class="btn btn-sm btn-outline-dark"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDetail"
                                onclick="setDetail('{{ $item[0] }}','{{ $item[1] }}','{{ $item[3] }}','{{ $item[4] }}','{{ $item[5] }}','{{ $item[2] }}','{{ $item[6] }}')">
                                Detail
                            </button>

                            <button class="btn btn-sm btn-success" onclick="markDone(this)">
                                Selesai
                            </button>

                        </div>
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Penyaluran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama Lansia</label>
                    <select id="inputNama" class="form-control">
                        <option>Siti Rahayu</option>
                        <option>Martha Kalalo</option>
                        <option>Johan Runtuwene</option>
                        <option>Yuliana Wenas</option>
                        <option>Hendrik Mandagi</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prioritas</label>
                    <select id="inputPrioritas" class="form-control">
                        <option>Prioritas Utama</option>
                        <option>Diprioritaskan</option>
                        <option>Cukup</option>
                        <option>Rendah</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Bantuan</label>
                    <select id="inputBantuan" class="form-control">
                        <option>Bantuan Kesehatan</option>
                        <option>Bantuan Sosial</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" id="inputTanggal" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-dark" onclick="tambahData()">Simpan</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Penyaluran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <strong>Nama:</strong>
                    <div id="d_nama" class="text-muted"></div>
                </div>

                <div class="mb-2">
                    <strong>Prioritas:</strong>
                    <span id="d_prioritas"></span>
                </div>

                <div class="mb-2">
                    <strong>Jenis Bantuan:</strong>
                    <div id="d_bantuan" class="text-muted"></div>
                </div>

                <div class="mb-2">
                    <strong>Tanggal:</strong>
                    <div id="d_tanggal" class="text-muted"></div>
                </div>

                <div class="mb-2">
                    <strong>Status:</strong>
                    <span id="d_status"></span>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function setDetail(nama, prioritas, bantuan, tanggal, status, warnaPrioritas, warnaStatus) {

    document.getElementById('d_nama').innerText = nama;
    document.getElementById('d_bantuan').innerText = bantuan;
    document.getElementById('d_tanggal').innerText = tanggal;

    document.getElementById('d_prioritas').innerHTML =
        `<span class="badge ${warnaPrioritas} rounded-pill px-3 py-2">${prioritas}</span>`;

    document.getElementById('d_status').innerHTML =
        `<span class="badge ${warnaStatus} rounded-pill px-3 py-2">${status}</span>`;
}

function markDone(btn) {
    let row = btn.closest('tr');
    let statusCell = row.querySelector('td:nth-child(5)');

    statusCell.innerHTML =
        `<span class="badge bg-success rounded-pill px-3 py-2">Disalurkan</span>`;

    btn.remove();
}

function getBadge(prioritas) {
    if (prioritas === "Prioritas Utama") return "bg-danger";
    if (prioritas === "Diprioritaskan") return "bg-warning text-dark";
    if (prioritas === "Cukup") return "bg-primary";
    return "bg-secondary";
}

function tambahData() {

    let nama = document.getElementById('inputNama').value;
    let prioritas = document.getElementById('inputPrioritas').value;
    let bantuan = document.getElementById('inputBantuan').value;
    let tanggal = document.getElementById('inputTanggal').value;

    if (!tanggal) {
        alert("Tanggal harus diisi!");
        return;
    }

    let badgeClass = getBadge(prioritas);

    let row = `
        <tr>
            <td class="fw-semibold">${nama}</td>
            <td><span class="badge rounded-pill px-3 py-2 ${badgeClass}">${prioritas}</span></td>
            <td class="text-muted">${bantuan}</td>
            <td class="text-muted small">${tanggal}</td>
            <td><span class="badge bg-secondary rounded-pill px-3 py-2">Belum Disalurkan</span></td>
            <td>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalDetail"
                        onclick="setDetail('${nama}','${prioritas}','${bantuan}','${tanggal}','Belum Disalurkan','${badgeClass}','bg-secondary')">
                        Detail
                    </button>
                    <button class="btn btn-sm btn-success" onclick="markDone(this)">
                        Selesai
                    </button>
                </div>
            </td>
        </tr>
    `;

    document.getElementById('penyaluranTable').insertAdjacentHTML('beforeend', row);

    let modal = bootstrap.Modal.getInstance(document.getElementById('modalTambah'));
    modal.hide();

    document.getElementById('inputTanggal').value = "";
}
</script>

@endsection
