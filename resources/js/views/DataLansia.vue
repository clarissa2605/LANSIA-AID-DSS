<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h3 class="fw-bold">Data Lansia</h3>
                <p class="text-muted mb-0">Manajemen data penerima bantuan</p>
            </div>
            <button
                class="btn btn-dark"
                @click="tambahData"
                :disabled="loading"
            >
                + Tambah Lansia
            </button>
        </div>

        <!-- ERROR -->
        <div v-if="error" class="alert alert-danger mb-4">
            <strong>Gagal memuat data:</strong> {{ error }}
        </div>

        <!-- LOADING STATE -->
        <div v-if="loading" class="dashboard-preview">
            <LoadingState message="Memuat data lansia..." />
        </div>

        <!-- EMPTY STATE -->
        <div v-else-if="lansiaData.length === 0" class="dashboard-preview">
            <EmptyState
                title="Belum ada data lansia"
                message="Mulai dengan menambahkan data lansia baru untuk memulai proses penilaian dan penyaluran bantuan."
                icon="users"
            >
                <template #action>
                    <button class="btn btn-dark" @click="tambahData">
                        + Tambah Data Lansia Pertama
                    </button>
                </template>
            </EmptyState>
        </div>

        <!-- SEARCH & TABLE -->
        <template v-else>
            <!-- SEARCH -->
            <div class="mb-3">
                <input
                    v-model="search"
                    type="text"
                    class="form-control"
                    placeholder="Cari nama lansia..."
                    style="text-align: left; max-width: 320px"
                />
            </div>

            <!-- TABLE -->
            <div class="dashboard-preview">
                <div
                    v-if="filtered.length === 0"
                    class="text-center text-muted py-4"
                >
                    <p>Tidak ada hasil pencarian untuk "{{ search }}"</p>
                </div>
                <table v-else class="app-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Alamat</th>
                            <th>Status Tinggal</th>
                            <th>Ekonomi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="l in filtered" :key="l.id">
                            <td class="fw-semibold">{{ l.nama }}</td>
                            <td>{{ l.umur }} tahun</td>
                            <td class="text-muted small">{{ l.alamat }}</td>
                            <td>
                                <StatusBadge
                                    :status="
                                        l.status_tinggal || 'Tidak Ada Data'
                                    "
                                    type="status"
                                />
                            </td>
                            <td>
                                <span
                                    class="badge bg-info text-light px-3 py-2"
                                >
                                    {{
                                        l.kategori_penghasilan
                                            ?.replace(/_/g, " ")
                                            .replace(/\b\w/g, (c) =>
                                                c.toUpperCase(),
                                            ) || "-"
                                    }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px">
                                    <button
                                        class="btn btn-sm btn-outline-primary"
                                        @click="editData(l)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="hapus(l)"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </div>

    <!-- MODAL TAMBAH DATA -->
    <div
        v-if="showModal"
        class="dss-modal-overlay"
        @click.self="showModal = false"
    >
        <div class="dss-modal">
            <div class="dss-modal-header">
                <h5>
                    {{ isEdit ? "Edit Data Lansia" : "Tambah Data Lansia" }}
                </h5>
                <button class="dss-modal-close" @click="showModal = false">
                    ×
                </button>
            </div>

            <div class="dss-modal-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label">NIK</label>
                        <input
                            v-model="form.nik"
                            type="text"
                            class="form-control"
                        />
                    </div>

                    <div>
                        <label class="form-label">Nama</label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="form-control"
                        />
                    </div>

                    <div>
                        <label class="form-label">Umur</label>
                        <input
                            v-model="form.umur"
                            type="number"
                            class="form-control"
                        />
                    </div>

                    <div>
                        <label class="form-label">Jenis Kelamin</label>
                        <select
                            v-model="form.jenis_kelamin"
                            class="form-control"
                        >
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label class="form-label">Alamat</label>
                        <input
                            v-model="form.alamat"
                            type="text"
                            class="form-control"
                        />
                    </div>

                    <div>
                        <label class="form-label">Kecamatan</label>
                        <input
                            v-model="form.kecamatan"
                            type="text"
                            class="form-control"
                        />
                    </div>

                    <div>
                        <label class="form-label">Status Tinggal</label>
                        <select
                            v-model="form.status_tinggal"
                            class="form-control"
                        >
                            <option value="tinggal_sendiri">
                                Tinggal Sendiri
                            </option>
                            <option value="bersama_pasangan">
                                Bersama Pasangan
                            </option>
                            <option value="bersama_keluarga">
                                Bersama Keluarga
                            </option>
                            <option value="tinggal_di_panti">
                                Tinggal di Panti
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Kondisi Kesehatan</label>
                        <select
                            v-model="form.kondisi_kesehatan"
                            class="form-control"
                        >
                            <option value="sehat">Sehat</option>
                            <option value="penyakit_ringan">
                                Penyakit Ringan
                            </option>
                            <option value="penyakit_kronis">
                                Penyakit Kronis
                            </option>
                            <option value="disabilitas">Disabilitas</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Kondisi Rumah</label>
                        <select
                            v-model="form.kondisi_rumah"
                            class="form-control"
                        >
                            <option value="rumah_layak">Rumah Layak</option>
                            <option value="rumah_cukup_layak">
                                Rumah Cukup Layak
                            </option>
                            <option value="rumah_tidak_layak">
                                Rumah Tidak Layak
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Kondisi Ekonomi</label>
                        <select
                            v-model="form.kategori_penghasilan"
                            class="form-control"
                        >
                            <option value="tidak_memiliki_penghasilan">
                                Tidak Memiliki Penghasilan
                            </option>
                            <option value="penghasilan_rendah">
                                Penghasilan Rendah
                            </option>
                            <option value="penghasilan_menengah">
                                Penghasilan Menengah
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="dss-modal-footer">
                <button class="btn btn-secondary" @click="showModal = false">
                    Batal
                </button>
                <button class="btn btn-dark" @click="simpan">
                    {{ isEdit ? "Update" : "Simpan" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useLansia } from "../composables/useLansia";
import StatusBadge from "../components/StatusBadge.vue";
import EmptyState from "../components/EmptyState.vue";
import LoadingState from "../components/LoadingState.vue";

const {
    showModal,
    isEdit,
    selectedId,
    search,
    form,
    loading,
    error,
    lansiaData,
    filtered,
    loadLansia,
    editData,
    hapus,
    simpan,
    tambahData,
} = useLansia();

onMounted(() => {
    loadLansia();
});
</script>
