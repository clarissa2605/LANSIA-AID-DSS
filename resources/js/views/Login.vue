<template>
    <div class="login-wrapper">
        <!-- NOISE (same as app body::before) -->

        <!-- LEFT PANEL -->
        <div class="login-left">
            <div class="login-left-inner">
                <div class="login-brand">
                    <div class="logo-mark"></div>
                    <div class="logo-text brand">Si<span>LANSIA</span></div>
                </div>

                <h2 class="login-heading">
                    Sistem Pendukung<br />
                    Keputusan <em>Bantuan Lansia</em>
                </h2>

                <p class="login-desc">
                    Platform terintegrasi untuk menentukan prioritas penerima
                    bantuan lansia secara objektif menggunakan metode AHP dan
                    TOPSIS.
                </p>

                <div class="login-features">
                    <div
                        class="login-feature-item"
                        v-for="f in features"
                        :key="f.label"
                    >
                        <div class="login-feature-icon">
                            <component :is="f.icon" :size="16" />
                        </div>
                        <div>
                            <div class="login-feature-title">{{ f.label }}</div>
                            <div class="login-feature-sub">{{ f.sub }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL — FORM -->
        <div class="login-right">
            <div class="login-card">
                <h4 class="login-card-title">Masuk ke Sistem</h4>
                <p class="login-card-sub">
                    Pilih role dan masukkan kredensial Anda.
                </p>

                <!-- ROLE TOGGLE -->
                <div class="role-toggle">
                    <button
                        :class="[
                            'role-btn',
                            selectedRole === 'admin' && 'active',
                        ]"
                        @click="selectedRole = 'admin'"
                    >
                        <ShieldCheck :size="15" />
                        Admin
                    </button>
                    <button
                        :class="[
                            'role-btn',
                            selectedRole === 'petugas' && 'active',
                        ]"
                        @click="selectedRole = 'petugas'"
                    >
                        <UserCheck :size="15" />
                        Petugas
                    </button>
                </div>

                <!-- ROLE DESCRIPTION -->
                <div class="role-desc-box">
                    <p v-if="selectedRole === 'admin'">
                        <strong>Admin</strong> mengelola kriteria, menjalankan
                        AHP &amp; SAW, menetapkan penerima bantuan, mengelola
                        akun pengguna, dan mengawasi seluruh proses penyaluran.
                    </p>
                    <p v-else>
                        <strong>Petugas</strong> mengumpulkan data lansia,
                        melihat hasil prioritas, menyalurkan bantuan,
                        memperbarui status distribusi, dan memantau proses
                        sebagai penghubung antara lansia dan sistem.
                    </p>
                </div>

                <!-- INPUT -->
                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        v-model="email"
                        type="email"
                        class="form-control"
                        style="text-align: left"
                        :placeholder="
                            selectedRole === 'admin'
                                ? 'admin@silansia.com'
                                : 'responden@silansia.com'
                        "
                        @keyup.enter="login"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        v-model="password"
                        :type="showPass ? 'text' : 'password'"
                        class="form-control"
                        style="text-align: left"
                        placeholder="Masukkan password..."
                        @keyup.enter="login"
                    />
                    <div style="text-align: right; margin-top: 4px">
                        <a
                            @click="showPass = !showPass"
                            style="
                                font-size: 12px;
                                color: var(--muted);
                                cursor: pointer;
                            "
                        >
                            {{ showPass ? "Sembunyikan" : "Tampilkan" }}
                            password
                        </a>
                    </div>
                </div>

                <!-- ERROR -->
                <div v-if="error" class="login-error">
                    {{ error }}
                </div>

                <button class="btn-analyze w-100" @click="login">
                    <LogIn :size="15" />
                    Masuk sebagai
                    {{ selectedRole === "admin" ? "Admin" : "Petugas" }}
                </button>

                <p class="login-hint">
                    Admin: admin@silansia.com / admin123
                    <br />
                    Responden: responden@silansia.com / responden123
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { authStore } from "../store/auth.js";

import {
    ShieldCheck,
    UserCheck,
    LogIn,
    SlidersHorizontal,
    BarChart3,
    Package,
    Activity,
} from "lucide-vue-next";

const router = useRouter();

const selectedRole = ref("admin");

const email = ref("");
const password = ref("");
const showPass = ref(false);
const error = ref("");
const loading = ref(false);

const features = [
    {
        icon: SlidersHorizontal,
        label: "Kriteria AHP",
        sub: "Pembobotan kriteria penilaian objektif",
    },
    {
        icon: BarChart3,
        label: "Prioritas SAW",
        sub: "Perangkingan lansia penerima bantuan",
    },
    {
        icon: Package,
        label: "Penyaluran Bantuan",
        sub: "Distribusi tercatat & terpantau",
    },
    {
        icon: Activity,
        label: "Monitoring",
        sub: "Progress distribusi real-time",
    },
];

async function login() {
    error.value = "";

    if (!email.value.trim()) {
        error.value = "Email tidak boleh kosong.";
        return;
    }

    if (!password.value.trim()) {
        error.value = "Password tidak boleh kosong.";
        return;
    }

    try {
        loading.value = true;

        const user = await authStore.login(email.value, password.value);

        switch (user.role) {
            case "admin":
                router.push("/dashboard");
                break;

            case "petugas":
                router.push("/petugas/dashboard");
                break;

            default:
                error.value = "Role pengguna tidak dikenali.";
        }
    } catch (err) {
        error.value =
            err?.response?.data?.message ||
            "Login gagal. Periksa email dan password.";
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped>
.login-wrapper {
    display: flex;
    min-height: 100vh;
}

/* LEFT */
.login-left {
    flex: 1;
    background: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 48px;
    position: relative;
    overflow: hidden;
}

.login-left::before {
    content: "";
    position: absolute;
    top: -150px;
    right: -150px;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        rgba(201, 168, 76, 0.12) 0%,
        transparent 70%
    );
    pointer-events: none;
}

.login-left-inner {
    max-width: 420px;
    position: relative;
    z-index: 1;
}

.login-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
}

.login-brand .logo-text {
    font-size: 26px;
    color: var(--cream);
}
.login-brand .logo-text span {
    color: var(--gold-light);
}

.login-heading {
    font-family: "Playfair Display", serif;
    font-size: 34px;
    font-weight: 700;
    color: var(--cream);
    line-height: 1.2;
    margin-bottom: 18px;
    letter-spacing: -0.5px;
}

.login-heading em {
    font-style: italic;
    color: var(--gold-light);
}

.login-desc {
    font-size: 15px;
    color: rgba(248, 244, 237, 0.6);
    line-height: 1.7;
    margin-bottom: 36px;
    font-weight: 300;
}

.login-features {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.login-feature-item {
    display: flex;
    align-items: center;
    gap: 14px;
}

.login-feature-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold-light);
    flex-shrink: 0;
}

.login-feature-title {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--cream);
}

.login-feature-sub {
    font-size: 12px;
    color: rgba(248, 244, 237, 0.5);
    margin-top: 1px;
}

/* RIGHT */
.login-right {
    width: 460px;
    flex-shrink: 0;
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
}

.login-card {
    width: 100%;
    max-width: 380px;
}

.login-card-title {
    font-family: "Playfair Display", serif;
    font-size: 24px;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 6px;
}

.login-card-sub {
    font-size: 13.5px;
    color: var(--muted);
    margin-bottom: 28px;
}

/* ROLE TOGGLE */
.role-toggle {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.role-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: var(--warm-white);
    color: var(--muted);
    font-size: 13.5px;
    font-weight: 600;
    font-family: "DM Sans", sans-serif;
    cursor: pointer;
    transition: all 0.18s;
}

.role-btn.active {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--cream);
}

/* ROLE DESC */
.role-desc-box {
    background: rgba(201, 168, 76, 0.08);
    border: 1px solid rgba(201, 168, 76, 0.25);
    border-radius: 10px;
    padding: 11px 14px;
    margin-bottom: 20px;
    font-size: 13px;
    color: var(--navy);
    line-height: 1.6;
}

.login-error {
    background: rgba(192, 96, 58, 0.1);
    border: 1px solid rgba(192, 96, 58, 0.3);
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 13px;
    color: var(--terracotta);
    margin-bottom: 14px;
}

.login-hint {
    text-align: center;
    font-size: 12px;
    color: var(--muted);
    margin-top: 14px;
    margin-bottom: 0;
}

.w-100 {
    width: 100% !important;
}

@media (max-width: 768px) {
    .login-left {
        display: none;
    }
    .login-right {
        width: 100%;
    }
}
</style>
