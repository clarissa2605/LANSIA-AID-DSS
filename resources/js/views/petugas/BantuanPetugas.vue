<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Penyaluran Bantuan</h2>
                <p class="text-muted mb-0">
                    Kelola dan perbarui status distribusi bantuan lansia
                </p>
            </div>
        </div>

        <!-- INFO ROLE -->
        <div class="alert-light-box mb-4">
            <strong>Tugas Petugas:</strong> Salurkan bantuan kepada lansia
            sesuai urutan prioritas, lalu perbarui status distribusi di bawah
            ini agar admin dapat memantau perkembangannya.
        </div>

        <!-- FILTER STATUS -->
        <div class="dss-flex-between mb-3" style="flex-wrap: wrap; gap: 8px">
            <div style="display: flex; gap: 6px; flex-wrap: wrap">
                <button
                    v-for="f in filterOpts"
                    :key="f.val"
                    :class="[
                        'btn btn-sm',
                        activeFilter === f.val
                            ? 'btn-dark'
                            : 'btn-outline-dark',
                    ]"
                    @click="activeFilter = f.val"
                >
                    {{ f.label }}
                </button>
            </div>
            <input
                v-model="search"
                class="form-control"
                style="text-align: left; max-width: 220px; padding: 6px 12px"
                placeholder="Cari nama..."
            />
        </div>

        <div v-if="loading" class="alert alert-info mb-3">
            Memuat data bantuan...
        </div>

        <div v-if="error" class="alert alert-danger mb-3">
            {{ error }}
        </div>

        <!-- TABLE -->
        <div class="dashboard-preview">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama Lansia</th>
                        <th>Prioritas</th>
                        <th>Jenis Bantuan</th>
                        <th>Tanggal Rencana</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filtered" :key="item.id">
                        <td class="fw-semibold">
                            {{ item.nama }}
                        </td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="{
                                    'bg-danger': item.urgensi === 'tinggi',
                                    'bg-warning text-dark':
                                        item.urgensi === 'sedang',
                                    'bg-secondary': item.urgensi === 'rendah',
                                }"
                            >
                                {{ item.urgensi }}
                            </span>
                        </td>
                        <td class="text-muted">{{ item.jenis }}</td>
                        <td class="text-muted small">
                            {{
                                new Date(item.created_at).toLocaleDateString(
                                    "id-ID",
                                )
                            }}
                        </td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="statusClassFor(item.status)"
                            >
                                {{
                                    item.status === "pending"
                                        ? "Belum Disalurkan"
                                        : item.status === "diproses"
                                          ? "Diproses"
                                          : "Disalurkan"
                                }}
                            </span>
                        </td>
                        <td>
                            <div
                                style="display: flex; gap: 6px; flex-wrap: wrap"
                            >
                                <button
                                    v-if="item.status === 'pending'"
                                    class="btn btn-sm btn-outline-dark"
                                    @click="ubahStatus(item, 'diproses')"
                                >
                                    Mulai Proses
                                </button>

                                <button
                                    v-if="item.status === 'diproses'"
                                    class="btn btn-sm btn-success"
                                    @click="ubahStatus(item, 'disalurkan')"
                                >
                                    Konfirmasi Selesai
                                </button>

                                <button
                                    v-if="item.status === 'disalurkan'"
                                    class="btn btn-sm btn-outline-dark"
                                    disabled
                                    style="opacity: 0.4; cursor: default"
                                >
                                    Selesai ✓
                                </button>

                                <button
                                    class="btn btn-sm btn-outline-dark"
                                    @click="openDetail(item)"
                                >
                                    Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td
                            colspan="6"
                            style="
                                text-align: center;
                                color: var(--muted);
                                padding: 32px;
                            "
                        >
                            Tidak ada data untuk filter ini.
                        </td>
                    </tr>
                </tbody>
            </table>
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
            <div
                class="dss-modal-body"
                style="display: flex; flex-direction: column; gap: 14px"
            >
                <div v-for="(val, label) in detailRows" :key="label">
                    <div class="form-label">{{ label }}</div>
                    <div
                        style="
                            font-size: 14px;
                            color: var(--navy);
                            font-weight: 500;
                        "
                    >
                        {{ val }}
                    </div>
                </div>

                <!-- UPDATE STATUS INLINE -->
                <div>
                    <div class="form-label">Perbarui Status</div>
                    <select
                        v-model="detailItem.statusVal"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option value="pending">Belum Disalurkan</option>

                        <option value="diproses">Diproses</option>

                        <option value="disalurkan">Disalurkan</option>
                    </select>
                </div>

                <div>
                    <div class="form-label">Catatan Petugas</div>
                    <textarea
                        v-model="detailItem.catatan"
                        class="form-control"
                        style="text-align: left; min-height: 70px"
                        placeholder="Tambahkan catatan..."
                    ></textarea>
                </div>
            </div>
            <div class="dss-modal-footer">
                <button class="btn btn-secondary" @click="detailItem = null">
                    Tutup
                </button>
                <button class="btn btn-dark" @click="simpanDetail">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useBantuan } from "../../composables/useBantuan.js";

const {
    loading,
    error,

    bantuan: penyaluran,

    loadBantuan,
    verifikasi,
    salurkan,
} = useBantuan();

const search = ref("");
const activeFilter = ref("semua");
const detailItem = ref(null);

const filterOpts = [
    { val: "semua", label: "Semua" },
    { val: "pending", label: "Belum Disalurkan" },
    { val: "diproses", label: "Diproses" },
    { val: "disalurkan", label: "Sudah Disalurkan" },
];

const filtered = computed(() => {
    if (!penyaluran.value) return [];

    return penyaluran.value.filter((item) => {
        const nama = item.nama || "";

        const matchSearch = nama
            .toLowerCase()
            .includes(search.value.toLowerCase());

        const matchFilter =
            activeFilter.value === "semua" ||
            item.status === activeFilter.value;

        return matchSearch && matchFilter;
    });
});
const detailRows = computed(() => {
    if (!detailItem.value) return {};

    return {
        "Nama Lansia": detailItem.value.nama,

        Status: detailItem.value.status,

        Keterangan: detailItem.value.keterangan || "-",

        "Tanggal Pengajuan": detailItem.value.created_at
            ? new Date(detailItem.value.created_at).toLocaleDateString("id-ID")
            : "-",
    };
});

function statusClassFor(status) {
    switch (status) {
        case "disalurkan":
            return "bg-success";

        case "diproses":
            return "bg-warning text-dark";

        default:
            return "bg-secondary";
    }
}

async function ubahStatus(item, newStatus) {
    try {
        if (newStatus === "diproses") {
            await verifikasi(item.id);
        }

        if (newStatus === "disalurkan") {
            await salurkan(item.id);
        }

        await loadBantuan();
    } catch (err) {
        console.error(err);
    }
}

function openDetail(item) {
    detailItem.value = {
        ...item,
        statusVal: item.status,
        catatan: item.catatan || "",
    };
}

async function simpanDetail() {
    try {
        if (
            detailItem.value.statusVal === "diproses" &&
            detailItem.value.status !== "diproses"
        ) {
            await verifikasi(detailItem.value.id);
        }

        if (
            detailItem.value.statusVal === "disalurkan" &&
            detailItem.value.status !== "disalurkan"
        ) {
            await salurkan(detailItem.value.id);
        }

        detailItem.value = null;

        await loadBantuan();
    } catch (err) {
        console.error(err);
    }
}

onMounted(async () => {
    await loadBantuan();
});
</script>
