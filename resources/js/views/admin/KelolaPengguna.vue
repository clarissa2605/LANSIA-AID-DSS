<template>
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="dss-flex-between mb-4">
            <div>
                <h2 class="page-title">Kelola Pengguna</h2>
                <p class="text-muted mb-0">
                    Manajemen akun Admin dan Petugas sistem
                </p>
            </div>
            <button class="btn btn-dark" @click="showModal = true">
                + Tambah Pengguna
            </button>
        </div>

        <!-- STATS -->
        <div class="row-metrics mb-4">
            <div class="metric-card accent-red">
                <div class="metric-top"><ShieldCheck :size="20" /></div>
                <div class="metric-val">{{ totalAdmin }}</div>
                <div class="metric-lbl">Admin</div>
            </div>
            <div class="metric-card accent-gold">
                <div class="metric-top"><UserCheck :size="20" /></div>
                <div class="metric-val">{{ totalResponden }}</div>
                <div class="metric-lbl">Responden</div>
            </div>
            <div class="metric-card accent-green">
                <div class="metric-top"><Users :size="20" /></div>
                <div class="metric-val">{{ users.length }}</div>
                <div class="metric-lbl">Total Pengguna</div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="dashboard-preview">
            <div class="dss-flex-between mb-3">
                <h6 class="section-title mb-0">Daftar Pengguna</h6>
                <input
                    v-model="search"
                    class="form-control"
                    style="
                        text-align: left;
                        max-width: 240px;
                        padding: 7px 12px;
                    "
                    placeholder="Cari nama..."
                />
            </div>
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="u in filtered" :key="u.id">
                        <td class="fw-semibold">{{ u.nama }}</td>
                        <td class="text-muted">{{ u.username }}</td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="
                                    u.role === 'admin'
                                        ? 'bg-danger'
                                        : 'bg-primary'
                                "
                            >
                                {{ u.role === "admin" ? "Admin" : "Responden" }}
                            </span>
                        </td>
                        <td>
                            <span
                                class="badge rounded-pill px-3 py-2"
                                :class="u.aktif ? 'bg-success' : 'bg-secondary'"
                            >
                                {{ u.aktif ? "Aktif" : "Nonaktif" }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px">
                                <button
                                    class="btn btn-sm btn-outline-dark"
                                    @click="edit(u)"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-sm"
                                    :class="
                                        u.aktif
                                            ? 'btn-outline-danger'
                                            : 'btn-success'
                                    "
                                    @click="toggleAktif(u)"
                                >
                                    {{ u.aktif ? "Nonaktifkan" : "Aktifkan" }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH/EDIT -->
    <div v-if="showModal" class="dss-modal-overlay" @click.self="closeModal">
        <div class="dss-modal">
            <div class="dss-modal-header">
                <h5>{{ isEdit ? "Edit Pengguna" : "Tambah Pengguna" }}</h5>
                <button class="dss-modal-close" @click="closeModal">×</button>
            </div>
            <div class="dss-modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input
                        v-model="form.nama"
                        type="text"
                        class="form-control"
                        style="text-align: left"
                        placeholder="Nama..."
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input
                        v-model="form.username"
                        type="text"
                        class="form-control"
                        style="text-align: left"
                        placeholder="username..."
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select
                        v-model="form.role"
                        class="form-control"
                        style="text-align: left"
                    >
                        <option value="admin">Admin</option>
                        <option value="responden">Responden</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{
                        isEdit
                            ? "Password Baru (kosongkan jika tidak diubah)"
                            : "Password"
                    }}</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control"
                        style="text-align: left"
                        placeholder="Password..."
                    />
                </div>
            </div>
            <div class="dss-modal-footer">
                <button class="btn btn-secondary" @click="closeModal">
                    Batal
                </button>
                <button class="btn btn-dark" @click="simpan">Simpan</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { ShieldCheck, UserCheck, Users } from "lucide-vue-next";

const showModal = ref(false);
const isEdit = ref(false);
const search = ref("");

const form = ref({
    id: null,
    nama: "",
    username: "",
    role: "responden",
    password: "",
});

const users = ref([]);

let nextId = 5;

async function loadUsers() {
    try {
        const { data } = await axios.get("/users");

        users.value = data.map((user) => ({
            id: user.id,
            nama: user.name,
            username: user.email,
            role: user.role,
            aktif: true,
        }));
    } catch (error) {
        console.error(error);
    }
}

onMounted(loadUsers);

const totalAdmin = computed(
    () => users.value.filter((u) => u.role === "admin").length,
);

const totalResponden = computed(
    () => users.value.filter((u) => u.role === "responden").length,
);

const filtered = computed(() =>
    users.value.filter((u) =>
        u.nama.toLowerCase().includes(search.value.toLowerCase()),
    ),
);

function edit(u) {
    form.value = {
        id: u.id,
        nama: u.nama,
        username: u.username,
        role: u.role,
        password: "",
    };

    isEdit.value = true;
    showModal.value = true;
}

function toggleAktif(u) {
    u.aktif = !u.aktif;
}

function closeModal() {
    showModal.value = false;
    isEdit.value = false;

    form.value = {
        id: null,
        nama: "",
        username: "",
        role: "responden",
        password: "",
    };
}

function simpan() {
    if (!form.value.nama || !form.value.username) {
        alert("Nama dan username wajib diisi!");
        return;
    }

    if (isEdit.value) {
        const u = users.value.find((x) => x.id === form.value.id);

        if (u) {
            u.nama = form.value.nama;
            u.username = form.value.username;
            u.role = form.value.role;
        }
    } else {
        users.value.push({
            id: nextId++,
            nama: form.value.nama,
            username: form.value.username,
            role: form.value.role,
            aktif: true,
        });
    }

    closeModal();
}
</script>
