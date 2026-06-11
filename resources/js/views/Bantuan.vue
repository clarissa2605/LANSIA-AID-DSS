<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Penyaluran Bantuan</h2>
                <p class="text-muted mb-0">
                    Distribusi bantuan berdasarkan hasil prioritas
                </p>
            </div>
            <button class="btn btn-dark" @click="openTambah">
                + Tambah Penyaluran
            </button>
        </div>

        <!-- TABLE -->
        <div class="dashboard-preview">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama Lansia</th>
                        <th>Prioritas</th>
                        <th>Jenis Bantuan</th>
                        <th>Urgensi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in penyaluran" :key="item.id">
                        <td class="fw-semibold">{{ item.nama }}</td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="item.prioClass"
                                >{{ item.prioritas }}</span
                            >
                        </td>
                        <td class="text-muted">{{ item.jenis }}</td>
                        <td class="text-muted">{{ item.urgensi }}</td>
                        <td class="text-muted small">{{ item.tanggal }}</td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="item.statusClass"
                                >{{ item.status }}</span
                            >
                        </td>
                        <td>
                            <div
                                style="display: flex; gap: 6px; flex-wrap: wrap"
                            >
                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    @click="editItem(item)"
                                >
                                    Edit
                                </button>

                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    @click="hapusItem(item)"
                                >
                                    Hapus
                                </button>

                                <button
                                    class="btn btn-sm btn-outline-dark"
                                    @click="openDetail(item)"
                                >
                                    Detail
                                </button>

                                <button
                                    v-if="item.status !== 'Disalurkan'"
                                    class="btn btn-sm btn-success"
                                    @click="markDone(item)"
                                >
                                    Selesai
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div
        v-if="showTambah"
        class="dss-modal-overlay"
        @click.self="showTambah = false"
    >
        <div class="dss-modal">
            <div class="dss-modal-header">
                <h5>
                    {{ isEdit ? "Edit Penyaluran" : "Tambah Penyaluran" }}
                </h5>
                <button class="dss-modal-close" @click="showTambah = false">
                    ×
                </button>
            </div>
            <div class="dss-modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lansia</label>
                    <select
                        v-model="form.lansia_id"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option :value="null" disabled>
                            -- Pilih Lansia --
                        </option>
                        <option
                            v-for="l in daftarLansia"
                            :key="l.id"
                            :value="l.id"
                        >
                            {{ l.nama }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Bantuan</label>
                    <select
                        v-model="form.jenis"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option>Bantuan Kesehatan</option>
                        <option>Bantuan Sosial</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Urgensi</label>
                    <select
                        v-model="form.urgensi"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea
                        v-model="form.catatan"
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan catatan tambahan (opsional)"
                    ></textarea>
                </div>
            </div>
            <div class="dss-modal-footer">
                <button class="btn btn-secondary" @click="showTambah = false">
                    Batal
                </button>
                <button class="btn btn-dark" @click="simpanData">
                    {{ isEdit ? "Update" : "Simpan" }}
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div
        v-if="detailItem"
        class="dss-modal-overlay"
        @click.self="detailItem = null"
    >
        <div class="dss-modal">
            <div class="dss-modal-header">
                <h5>Detail Penyaluran</h5>
                <button class="dss-modal-close" @click="detailItem = null">
                    ×
                </button>
            </div>
            <div class="dss-modal-body">
                <div class="mb-2">
                    <strong>Nama:</strong>
                    <div class="text-muted">{{ detailItem.nama }}</div>
                </div>
                <div class="mb-2">
                    <strong>Prioritas:</strong><br />
                    <span
                        class="badge rounded-pill px-3 py-2 mt-1"
                        :class="detailItem.prioClass"
                        >{{ detailItem.prioritas }}</span
                    >
                </div>
                <div class="mb-2">
                    <strong>Jenis Bantuan:</strong>
                    <div class="text-muted">{{ detailItem.jenis }}</div>
                </div>
                <div class="mb-2">
                    <strong>Urgensi:</strong>
                    <div class="text-muted">{{ detailItem.urgensi }}</div>
                </div>
                <div class="mb-2">
                    <strong>Tanggal:</strong>
                    <div class="text-muted">{{ detailItem.tanggal }}</div>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong><br />
                    <span
                        class="badge rounded-pill px-3 py-2 mt-1"
                        :class="detailItem.statusClass"
                        >{{ detailItem.status }}</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";

import { useLansia } from "../composables/useLansia.js";
import { usePengajuanBantuan } from "../composables/usePengajuanBantuan.js";

import { penyaluranService } from "../services/penyaluranService.js";

const showTambah = ref(false);
const detailItem = ref(null);

const isEdit = ref(false);
const selectedPengajuanId = ref(null);

const loading = ref(false);
const error = ref("");

const penyaluran = ref([]);

const { createPengajuan, editPengajuan, updateStatus, deletePengajuan } =
    usePengajuanBantuan();

const { lansiaData, loadLansia } = useLansia();

const form = ref({
    lansia_id: null,
    jenis: "Bantuan Kesehatan",
    urgensi: "rendah",
    catatan: "",
});

const daftarLansia = computed(() =>
    lansiaData.value.map((item) => ({
        id: item.id,
        nama: item.nama,
    })),
);

onMounted(async () => {
    await loadLansia();
    await loadPenerimaBantuan();
});

async function loadPenerimaBantuan() {
    loading.value = true;
    error.value = "";

    try {
        const response = await penyaluranService.getAll();

        penyaluran.value = (response || []).map(mapPenerima);

        if (penyaluran.value.length > 0 && !form.value.lansia_id) {
            form.value.lansia_id = penyaluran.value[0].lansia_id;
        }
    } catch (err) {
        console.error(err);

        error.value =
            err?.response?.data?.message || "Gagal memuat data penyaluran";
    } finally {
        loading.value = false;
    }
}

function mapPenerima(item) {
    return {
        id: item.pengajuan_id,
        pengajuan_id: item.pengajuan_id,
        lansia_id: item.lansia_id,

        nama: item.nama,

        prioritas: `Rank #${item.rank}`,

        prioClass:
            item.rank === 1
                ? "bg-danger"
                : item.rank <= 3
                  ? "bg-warning text-dark"
                  : "bg-primary",

        jenis: item.jenis || "Bantuan Sosial",

        urgensi: item.urgensi || "rendah",

        catatan: item.catatan || "",

        tanggal: item.tanggal_pengajuan || "-",

        status: formatStatus(item.status),

        statusRaw: item.status,

        statusClass: getStatusClass(item.status),
    };
}

function formatStatus(status) {
    return (
        {
            pending: "Belum Disalurkan",
            diproses: "Diproses",
            disalurkan: "Disalurkan",
            ditolak: "Ditolak",
        }[status] ?? status
    );
}

function getStatusClass(status) {
    if (status === "disalurkan") {
        return "bg-success";
    }

    if (status === "diproses") {
        return "bg-warning text-dark";
    }

    if (status === "ditolak") {
        return "bg-danger";
    }

    return "bg-secondary";
}

async function simpanData() {
    try {
        if (isEdit.value) {
            await editPengajuan(selectedPengajuanId.value, {
                jenis: form.value.jenis,
                urgensi: form.value.urgensi,
                catatan: form.value.catatan,
            });
        } else {
            await createPengajuan({
                lansia_id: form.value.lansia_id,
                jenis: form.value.jenis,
                urgensi: form.value.urgensi,
                catatan: form.value.catatan,
            });
        }

        resetForm();

        isEdit.value = false;
        selectedPengajuanId.value = null;

        showTambah.value = false;

        await loadPenerimaBantuan();
    } catch (err) {
        alert(err?.response?.data?.message || "Gagal menyimpan data.");
    }
}

function editItem(item) {
    isEdit.value = true;

    selectedPengajuanId.value = item.pengajuan_id;

    form.value = {
        lansia_id: item.lansia_id,
        jenis: item.jenis,
        urgensi: item.urgensi,
        catatan: item.catatan || "",
    };

    showTambah.value = true;
}

async function hapusItem(item) {
    const confirmed = confirm(`Hapus bantuan untuk ${item.nama}?`);

    if (!confirmed) return;

    try {
        await deletePengajuan(item.pengajuan_id);

        await loadPenerimaBantuan();
    } catch (err) {
        console.error(err);

        alert(err?.response?.data?.message || "Gagal menghapus data.");
    }
}

async function markDone(item) {
    try {
        await updateStatus(item.pengajuan_id, {
            status: "disalurkan",
        });

        await loadPenerimaBantuan();
    } catch (err) {
        console.error(err);

        alert(err?.response?.data?.message || "Gagal memperbarui status.");
    }
}

function openTambah() {
    resetForm();

    isEdit.value = false;
    selectedPengajuanId.value = null;

    showTambah.value = true;
}

function resetForm() {
    form.value = {
        lansia_id: null,
        jenis: "Bantuan Kesehatan",
        urgensi: "rendah",
        catatan: "",
    };
}

function openDetail(item) {
    detailItem.value = item;
}
</script>
