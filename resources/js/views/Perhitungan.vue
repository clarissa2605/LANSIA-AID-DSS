<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="mb-4">
            <h2>Perhitungan Prioritas</h2>
            <p class="text-muted mb-0">
                Menentukan lansia yang diprioritaskan menerima bantuan
            </p>
        </div>

        <!-- PENJELASAN -->
        <div class="alert-light-box mb-4">
            <strong>Penjelasan:</strong><br />
            Sistem akan mengolah nilai penilaian untuk menghasilkan urutan
            prioritas lansia berdasarkan tingkat kebutuhan bantuan.
        </div>

        <!-- MAIN ACTION -->
        <div class="dashboard-preview text-center mb-4">
            <h5 class="mb-2">Proses Perhitungan</h5>
            <p class="text-muted">Klik tombol untuk memproses data</p>

            <button
                class="btn btn-dark mt-2"
                @click="proses"
                :disabled="loading"
            >
                {{ loading ? "Menghitung ranking..." : "Hitung Ranking" }}
            </button>

            <!-- ERROR STATE -->
            <div v-if="error" class="alert alert-danger mt-3">
                <strong>⚠️ Gagal:</strong> {{ error }}
            </div>

            <!-- EMPTY DATA STATE -->
            <div v-else-if="status === 'empty'" class="alert alert-info mt-3">
                <div v-if="incomplete.length > 0">
                    <strong>ℹ️ Data Penilaian Belum Lengkap</strong>
                    <p class="mb-2 mt-2">
                        Lengkapi penilaian untuk semua lansia sebelum menghitung
                        ranking.
                    </p>
                    <p class="mb-1 fw-semibold">
                        Lansia yang masih kurang penilaian:
                    </p>
                    <ul class="mb-0">
                        <li
                            v-for="item in incomplete"
                            :key="item.lansia_id"
                            class="small"
                        >
                            <strong>{{ item.nama }}</strong> - Kurang
                            {{
                                item.total_kriteria - item.penilaian_count
                            }}
                            kriteria
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <strong>ℹ️ Belum Ada Data Ranking</strong>
                    <p class="mb-0 mt-2">
                        {{ warning || "Lakukan perhitungan terlebih dahulu." }}
                    </p>
                </div>
            </div>

            <!-- PARTIAL DATA WARNING -->
            <div
                v-else-if="status === 'warning'"
                class="alert alert-warning mt-3"
            >
                <strong>⚠️ Peringatan Data Tidak Lengkap</strong>
                <p class="mb-2 mt-2">{{ warning }}</p>
                <p class="mb-1 fw-semibold">
                    Lansia dengan penilaian tidak lengkap:
                </p>
                <ul class="mb-0">
                    <li
                        v-for="item in incomplete"
                        :key="item.lansia_id"
                        class="small"
                    >
                        <strong>{{ item.nama }}</strong> - Kurang
                        {{
                            item.total_kriteria - item.penilaian_count
                        }}
                        kriteria
                    </li>
                </ul>
            </div>
        </div>

        <!-- HASIL -->
        <div class="dashboard-preview mb-4">
            <h6 class="fw-semibold mb-3">Hasil Prioritas</h6>

            <table
                v-if="hasil.length > 0"
                class="app-table"
                style="text-align: center"
            >
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama</th>
                        <th>Skor</th>
                        <th>Status Prioritas</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="r in hasil" :key="r.rank">
                        <td>
                            <strong>#{{ r.rank }}</strong>
                        </td>
                        <td class="fw-semibold">{{ r.nama }}</td>
                        <td>
                            <strong>{{ r.skor }}</strong>
                        </td>

                        <td>
                            <StatusBadge :status="r.status" type="priority" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-else class="text-center text-muted py-4">
                <p>
                    {{
                        loading
                            ? "Menghitung ranking..."
                            : "Belum ada hasil ranking. Klik 'Hitung Ranking' untuk memulai."
                    }}
                </p>
            </div>
        </div>

        <!-- TOGGLE DETAIL -->
        <div class="mb-3">
            <button
                class="btn btn-outline-dark"
                @click="showDetail = !showDetail"
                :disabled="hasil.length === 0"
            >
                {{
                    showDetail
                        ? "Sembunyikan Detail"
                        : "Lihat Detail Perhitungan"
                }}
            </button>
        </div>

        <!-- DETAIL -->
        <div v-if="showDetail && hasil.length > 0">
            <!-- STEP 1 -->
            <div class="dashboard-preview mb-4">
                <h6 class="fw-semibold mb-2">Data Penilaian</h6>

                <p class="text-muted small">
                    Nilai awal yang diberikan untuk setiap lansia
                </p>

                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Lansia</th>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="r in dataPenilaian" :key="r[0]">
                            <td v-for="(v, idx) in r" :key="idx">
                                {{ v }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- STEP 2 -->
            <div class="dashboard-preview mb-4">
                <h6 class="fw-semibold mb-2">Penyesuaian Nilai</h6>

                <p class="text-muted small">
                    Nilai disesuaikan agar bisa dibandingkan secara adil
                </p>

                <table class="app-table">
                    <tbody>
                        <tr v-for="r in dataNormalisasi" :key="r[0]">
                            <td v-for="(v, idx) in r" :key="idx">
                                {{ v }}
                            </td>
                        </tr>

                        <tr v-if="dataNormalisasi.length === 0">
                            <td>Data normalisasi belum tersedia</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- STEP 3 -->
            <div class="dashboard-preview mb-4">
                <h6 class="fw-semibold mb-2">Perhitungan Bobot</h6>

                <p class="text-muted small">
                    Nilai dikalikan dengan tingkat kepentingan kriteria
                </p>

                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Lansia</th>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="r in dataBobot" :key="r[0]">
                            <td v-for="(v, idx) in r" :key="idx">
                                {{ v }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- STEP 4 -->
            <div class="dashboard-preview mb-4">
                <h6 class="fw-semibold mb-2">Nilai Acuan</h6>

                <p class="text-muted small">
                    Digunakan sebagai pembanding untuk menentukan hasil akhir
                </p>

                <div class="dss-flex-between">
                    <div>
                        <p class="fw-semibold mb-1">Nilai Tertinggi</p>

                        <p class="text-muted">
                            {{ idealPositif }}
                        </p>
                    </div>

                    <div>
                        <p class="fw-semibold mb-1">Nilai Terendah</p>

                        <p class="text-muted">
                            {{ idealNegatif }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRanking } from "../composables/useRanking.js";
import StatusBadge from "../components/StatusBadge.vue";

const showDetail = ref(false);

const {
    loading,
    error,
    warning,
    status,
    hasil,
    incomplete,
    dataPenilaian,
    dataNormalisasi,
    dataBobot,
    idealPositif,
    idealNegatif,
    proses,
} = useRanking();

onMounted(() => {
    proses();
});
</script>
