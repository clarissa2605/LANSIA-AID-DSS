<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2>Penilaian Lansia</h2>
                <p class="text-muted mb-0">
                    Isi penilaian sesuai kondisi lansia
                </p>
            </div>
            <button
                class="btn btn-dark"
                @click="simpan"
                :disabled="!selectedLansia || savingLoading"
            >
                {{ savingLoading ? "Menyimpan..." : "Simpan Penilaian" }}
            </button>
        </div>

        <!-- ERROR ON LOAD -->
        <div v-if="error" class="alert alert-danger mb-4">
            <strong>Gagal memuat data:</strong> {{ error }}
        </div>

        <!-- PETUNJUK -->
        <div class="alert-light-box mb-4">
            <strong>Petunjuk:</strong><br />
            Berikan nilai dari <strong>1 sampai 5</strong> sesuai kondisi
            lansia. Semakin tinggi nilai, semakin diprioritaskan untuk menerima
            bantuan.
        </div>

        <!-- PILIH LANSIA -->
        <div class="dashboard-preview mb-4">
            <label class="form-label mb-2">Pilih Lansia</label>
            <select
                v-model="selectedLansia"
                class="form-control"
                style="text-align: left; max-width: 300px"
                :disabled="loading"
            >
                <option value="">-- Pilih Lansia --</option>
                <option v-for="l in daftarLansia" :key="l.id" :value="l.id">
                    {{ l.nama }}
                </option>
            </select>
            <div
                v-if="daftarLansia.length === 0 && !loading"
                class="text-muted small mt-2"
            >
                Belum ada data lansia. Tambahkan lansia di menu Data Lansia
                terlebih dahulu.
            </div>
        </div>

        <!-- INPUT NILAI -->
        <div class="dashboard-preview">
            <h6 class="mb-3 fw-semibold">Isi Penilaian</h6>

            <div
                v-if="kriteria.length === 0 && !loading"
                class="alert alert-info"
            >
                Belum ada kriteria. Buat kriteria terlebih dahulu.
            </div>

            <div v-else style="overflow-x: auto">
                <table class="app-table" style="text-align: center">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="k in kriteria" :key="k.id">
                            <td style="text-align: left">{{ k.nama }}</td>
                            <td>
                                <input
                                    v-model.number="k.nilai"
                                    type="number"
                                    min="1"
                                    max="5"
                                    class="form-control"
                                    style="width: 70px; margin: 0 auto"
                                    :disabled="!selectedLansia || savingLoading"
                                />
                            </td>
                            <td class="text-muted small">{{ k.keterangan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ERROR ON SAVE -->
        <div v-if="saveError" class="alert alert-danger mt-3">
            <strong>Gagal menyimpan:</strong> {{ saveError }}
        </div>

        <!-- SUCCESS -->
        <div
            v-if="savedMsg"
            class="alert-light-box mt-3"
            style="
                background: rgba(92, 122, 94, 0.12);
                border-color: var(--sage);
                color: var(--sage);
            "
        >
            ✓ Penilaian untuk <strong>{{ savedMsg }}</strong> berhasil disimpan!
        </div>

        <!-- LOADING INDICATOR -->
        <div v-if="loading" class="text-center text-muted mt-4">
            <p>Memuat data...</p>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";

import { penilaianService } from "../services/penilaianService.js";
import { lansiaService } from "../services/lansiaService.js";
import { kriteriaService } from "../services/kriteriaService.js";

const selectedLansia = ref("");
const savedMsg = ref("");
const loading = ref(false);
const savingLoading = ref(false);
const error = ref("");
const saveError = ref("");

const penilaianData = ref([]);
const daftarLansia = ref([]);
const kriteria = ref([]);

onMounted(() => {
    loadData();
});

watch(selectedLansia, () => {
    isiNilaiKriteria();
});

async function loadData() {
    loading.value = true;
    error.value = "";

    try {
        const [lansiaResponse, kriteriaResponse, penilaianResponse] =
            await Promise.all([
                lansiaService.getAll(),
                kriteriaService.getAll(),
                penilaianService.getAll(),
            ]);

        daftarLansia.value = lansiaResponse || [];

        kriteria.value = (kriteriaResponse || []).map((item) => ({
            id: item.id,
            nama: item.nama,
            atribut: item.atribut,
            nilai: 3,
            keterangan:
                item.atribut === "cost"
                    ? "Nilai lebih besar berarti lebih diprioritaskan"
                    : "Nilai lebih besar berarti lebih baik",
        }));

        penilaianData.value = penilaianResponse || [];
    } catch (err) {
        error.value =
            err?.response?.data?.message || err?.message || "Gagal memuat data";

        console.error(err);
    } finally {
        loading.value = false;
    }
}

function isiNilaiKriteria() {
    if (!selectedLansia.value) {
        kriteria.value.forEach((item) => {
            item.nilai = 3;
        });

        return;
    }

    kriteria.value.forEach((item) => {
        const existing = penilaianData.value.find(
            (row) =>
                row.lansia_id === selectedLansia.value &&
                row.kriteria_id === item.id,
        );

        item.nilai = existing ? Number(existing.nilai) : 3;
    });
}

async function simpan() {
    if (!selectedLansia.value) {
        saveError.value = "Pilih lansia terlebih dahulu!";
        return;
    }

    savingLoading.value = true;
    saveError.value = "";
    savedMsg.value = "";

    try {
        const payload = {
            lansia_id: selectedLansia.value,
            nilai: {},
        };

        kriteria.value.forEach((item) => {
            payload.nilai[item.id] = item.nilai;
        });

        await penilaianService.create(payload);

        const lansia = daftarLansia.value.find(
            (item) => item.id === selectedLansia.value,
        );

        savedMsg.value = lansia?.nama || "";

        setTimeout(() => {
            savedMsg.value = "";
        }, 3000);

        await loadData();
    } catch (err) {
        console.error(err);

        saveError.value =
            err?.response?.data?.message ||
            err?.message ||
            "Gagal menyimpan penilaian";
    } finally {
        savingLoading.value = false;
    }
}
</script>
