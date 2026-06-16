<template>
    <div>
        <!-- Halaman login: tidak pakai navbar -->
        <template v-if="isLoginPage">
            <router-view />
        </template>

        <!-- Halaman aplikasi: pakai navbar sesuai role -->
        <template v-else>
            <nav class="navbar navbar-expand-lg px-4 py-3">
                <!-- LOGO -->
                <div class="nav-logo">
                    <div class="logo-mark"></div>
                    <div class="logo-text brand">Si<span>LANSIA</span></div>
                </div>

                <!-- MENU -->
                <div class="navbar-collapse">
                    <ul
                        class="navbar-nav ms-auto"
                        style="
                            display: flex;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                            gap: 4px;
                            align-items: center;
                        "
                    >
                        <!-- ════════ MENU ADMIN ════════ -->
                        <template v-if="auth.isAdmin()">
                            <li>
                                <NavLink
                                    to="/dashboard"
                                    icon="LayoutDashboard"
                                    label="Dashboard"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/lansia"
                                    icon="Users"
                                    label="Data Lansia"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/kriteria"
                                    icon="SlidersHorizontal"
                                    label="Kriteria"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/penilaian"
                                    icon="ClipboardEdit"
                                    label="Penilaian"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/perhitungan"
                                    icon="Calculator"
                                    label="Perhitungan"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/hasil"
                                    icon="BarChart3"
                                    label="Hasil"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/bantuan"
                                    icon="Package"
                                    label="Penyaluran"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/monitoring"
                                    icon="Activity"
                                    label="Monitoring"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/admin/pengguna"
                                    icon="ShieldCheck"
                                    label="Pengguna"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/admin/laporan"
                                    icon="FileBarChart"
                                    label="Laporan"
                                />
                            </li>
                        </template>

                        <!-- ════════ MENU PETUGAS ════════ -->
                        <template v-else-if="auth.user?.role === 'petugas'">
                            <li>
                                <NavLink
                                    to="/petugas/dashboard"
                                    icon="LayoutDashboard"
                                    label="Dashboard"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/petugas/lansia"
                                    icon="Users"
                                    label="Data Lansia"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/penilaian"
                                    icon="ClipboardEdit"
                                    label="Penilaian"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/hasil"
                                    icon="BarChart3"
                                    label="Hasil Prioritas"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/petugas/bantuan"
                                    icon="Package"
                                    label="Penyaluran"
                                />
                            </li>
                            <li>
                                <NavLink
                                    to="/petugas/monitoring"
                                    icon="Activity"
                                    label="Monitoring"
                                />
                            </li>
                        </template>

                        <!-- ROLE BADGE + LOGOUT -->
                        <li
                            style="
                                margin-left: 8px;
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            "
                        >
                            <span
                                class="role-badge"
                                :class="
                                    auth.isAdmin()
                                        ? 'role-admin'
                                        : 'role-petugas'
                                "
                            >
                                <component
                                    :is="
                                        auth.isAdmin() ? ShieldCheck : UserCheck
                                    "
                                    :size="12"
                                />
                                {{ auth.isAdmin() ? "Admin" : "Petugas" }}
                            </span>
                            <span class="nav-user-name">
                                {{ auth.user?.name }}
                            </span>
                            <button
                                class="btn-logout"
                                @click="logout"
                                title="Keluar"
                            >
                                <LogOut :size="14" />
                            </button>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- CONTENT -->
            <div class="container-fluid px-5 main-content">
                <router-view v-slot="{ Component }">
                    <transition name="page-fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </div>
        </template>
    </div>
</template>

<script setup>
import { computed, defineComponent, h } from "vue";
import { useRoute, useRouter, RouterLink } from "vue-router";
import { authStore as auth } from "./store/auth.js";
import {
    LayoutDashboard,
    Users,
    SlidersHorizontal,
    ClipboardEdit,
    Calculator,
    BarChart3,
    Package,
    Activity,
    ShieldCheck,
    UserCheck,
    FileBarChart,
    LogOut,
} from "lucide-vue-next";

const route = useRoute();
const router = useRouter();

const isLoginPage = computed(() => route.path === "/login");

// ── Inline NavLink component ──
const iconMap = {
    LayoutDashboard,
    Users,
    SlidersHorizontal,
    ClipboardEdit,
    Calculator,
    BarChart3,
    Package,
    Activity,
    ShieldCheck,
    UserCheck,
    FileBarChart,
};

const NavLink = defineComponent({
    props: { to: String, icon: String, label: String },
    setup(props) {
        return () =>
            h(
                RouterLink,
                { to: props.to, custom: true },
                {
                    default: ({ navigate, isActive }) =>
                        h(
                            "a",
                            {
                                onClick: navigate,
                                class: ["nav-link", isActive && "active"],
                                style: "cursor:pointer;display:flex;align-items:center;gap:6px;padding:8px 12px;border-radius:8px;transition:all .2s",
                            },
                            [h(iconMap[props.icon], { size: 15 }), props.label],
                        ),
                },
            );
    },
});

async function logout() {
    await auth.logout();
    await router.replace("/login");
}
</script>

<style>
/*@import url('/style.css');*/

/* Page transition */
.page-fade-enter-active,
.page-fade-leave-active {
    transition:
        opacity 0.18s ease,
        transform 0.18s ease;
}
.page-fade-enter-from {
    opacity: 0;
    transform: translateY(8px);
}
.page-fade-leave-to {
    opacity: 0;
}

.nav-link.router-link-active {
    color: var(--navy) !important;
    font-weight: 600;
}

.container-fluid {
    width: 100%;
    padding-left: 12px;
    padding-right: 12px;
}
.px-5 {
    padding-left: 3rem !important;
    padding-right: 3rem !important;
}
.ms-auto {
    margin-left: auto !important;
}

/* ROLE BADGE */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.3px;
    font-family: "DM Sans", sans-serif;
}
.role-admin {
    background: rgba(192, 96, 58, 0.12);
    color: var(--terracotta);
    border: 1px solid rgba(192, 96, 58, 0.25);
}
.role-petugas {
    background: rgba(92, 122, 94, 0.12);
    color: var(--sage);
    border: 1px solid rgba(92, 122, 94, 0.25);
}

/* USER NAME in navbar */
.nav-user-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--navy);
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* LOGOUT BUTTON */
.btn-logout {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--warm-white);
    color: var(--muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
    flex-shrink: 0;
}
.btn-logout:hover {
    background: rgba(192, 96, 58, 0.1);
    color: var(--terracotta);
    border-color: rgba(192, 96, 58, 0.3);
}
</style>
