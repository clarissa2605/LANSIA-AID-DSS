<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h3 class="fw-bold">Kriteria Penilaian</h3>
                <p class="text-muted mb-0">Pengaturan bobot kriteria (AHP)</p>
            </div>
            <button class="btn btn-dark" @click="tambahKriteria">
                + Tambah Kriteria
            </button>
        </div>

        <!-- PENJELASAN -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Penjelasan Kriteria</h6>
            <p class="text-muted mb-3">
                Kriteria berikut digunakan untuk menentukan prioritas pemberian
                bantuan kepada lansia menggunakan metode AHP dan TOPSIS.
            </p>
            <ul class="mb-0 text-muted" style="padding-left: 18px">
                <li>
                    <strong>Kesehatan</strong> → Semakin buruk kondisi
                    kesehatan, semakin diprioritaskan (Cost)
                </li>
                <li>
                    <strong>Ekonomi</strong> → Semakin rendah kondisi ekonomi,
                    semakin diprioritaskan (Cost)
                </li>
                <li>
                    <strong>Status Tinggal</strong> → Lansia yang tinggal
                    sendiri lebih diprioritaskan (Cost)
                </li>
                <li>
                    <strong>Usia</strong> → Semakin tua usia, semakin
                    diprioritaskan (Benefit)
                </li>
            </ul>
        </div>

        <!-- ERROR -->
        <div v-if="error" class="alert alert-danger mb-3">
            {{ error }}
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

                <tbody v-if="loading">
                    <tr>
                        <td colspan="5" class="text-center">Memuat data...</td>
                    </tr>
                </tbody>

                <tbody v-else>
                    <tr v-for="k in kriteria" :key="k.id">
                        <td>{{ k.nama }}</td>
                        <td>{{ k.deskripsi }}</td>

                        <td>
                            <span
                                class="badge"
                                :class="
                                    k.jenis === 'Cost'
                                        ? 'bg-danger'
                                        : 'bg-success'
                                "
                            >
                                {{ k.jenis }}
                            </span>
                        </td>

                        <td>{{ k.bobot }}</td>

                        <td>
                            <button
                                class="btn btn-sm btn-outline-primary"
                                style="margin-right: 4px"
                                @click="editKriteria(k)"
                            >
                                Edit
                            </button>

                            <button
                                class="btn btn-sm btn-outline-danger"
                                @click="hapusKriteria(k)"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <tr v-if="kriteria.length === 0">
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data kriteria
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- AHP INFO -->
        <div class="alert-light-box mb-3">
            <strong>AHP (Analytical Hierarchy Process)</strong><br />
            Digunakan untuk menentukan bobot setiap kriteria melalui
            perbandingan berpasangan. Nilai menunjukkan tingkat kepentingan
            antar kriteria.
        </div>

        <!-- SKALA AHP -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-2">Skala Perbandingan AHP</h6>
            <table class="app-table" style="max-width: 300px">
                <tr v-for="s in skalaAhp" :key="s.val">
                    <td>{{ s.val }}</td>
                    <td>{{ s.label }}</td>
                </tr>
            </table>
        </div>

        <!-- AHP MATRIX -->
        <div class="dashboard-preview">
            <h6 class="mb-3 fw-semibold">Matriks Perbandingan (AHP)</h6>
            <div style="overflow-x: auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th v-for="k in kriteria" :key="k.id">
                                {{ k.nama }}
                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="matrix.length">
                        <tr v-for="(row, i) in matrix" :key="i">
                            <th>
                                {{ kriteria[i]?.nama || "-" }}
                            </th>

                            <td v-for="(val, j) in row" :key="j">
                                <span v-if="i === j"> 1 </span>

                                <input
                                    v-else
                                    v-model="matrix[i][j]"
                                    type="number"
                                    class="form-control"
                                    style="width: 70px"
                                />
                            </td>
                        </tr>
                    </tbody>

                    <tbody v-else>
                        <tr>
                            <td
                                :colspan="kriteria.length + 1"
                                class="text-center text-muted"
                            >
                                Belum ada kriteria untuk dibandingkan
                            </td>
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
    <div
        v-if="showModal"
        class="dss-modal-overlay"
        @click.self="showModal = false"
    >
        <div class="dss-modal">
            <div class="dss-modal-header">
                <h5>
                    {{ isEdit ? "Edit Kriteria" : "Tambah Kriteria" }}
                </h5>
                <button class="dss-modal-close" @click="showModal = false">
                    ×
                </button>
            </div>
            <div class="dss-modal-body">
                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input
                        v-model="form.kode"
                        type="text"
                        class="form-control"
                        style="text-align: left"
                        placeholder="C1"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Kriteria</label>
                    <input
                        v-model="form.nama"
                        type="text"
                        class="form-control"
                        style="text-align: left"
                        placeholder="Kesehatan"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>

                    <select
                        v-model="form.atribut"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option value="benefit">Benefit</option>

                        <option value="cost">Cost</option>
                    </select>
                </div>
            </div>
            <div class="dss-modal-footer">
                <button class="btn btn-secondary" @click="showModal = false">
                    Batal
                </button>
                <button class="btn btn-dark" @click="simpanKriteria">
                    {{ isEdit ? "Update" : "Simpan" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useKriteria } from "../composables/useKriteria";

const {
    showModal,
    loading,
    error,

    kriteria,
    matrix,
    skalaAhp,

    form,
    isEdit,

    loadKriteria,
    tambahKriteria,
    editKriteria,
    simpanKriteria,
    hapusKriteria,
} = useKriteria();

onMounted(() => {
    loadKriteria();
});
</script>
