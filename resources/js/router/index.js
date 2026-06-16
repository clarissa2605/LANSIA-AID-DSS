import { createRouter, createWebHistory } from "vue-router";
import { authStore } from "../store/auth.js";

// Shared Views
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import DataLansia from "../views/DataLansia.vue";
import Kriteria from "../views/Kriteria.vue";
import Penilaian from "../views/Penilaian.vue";
import Perhitungan from "../views/Perhitungan.vue";
import HasilPrioritas from "../views/HasilPrioritas.vue";
import Bantuan from "../views/Bantuan.vue";
import Monitoring from "../views/Monitoring.vue";

// Admin Views
import KelolaPengguna from "../views/admin/KelolaPengguna.vue";
import LaporanAdmin from "../views/admin/LaporanAdmin.vue";

// Responden Views
import DashboardResponden from "../views/Dashboard.vue";
import BantuanResponden from "../views/Bantuan.vue";
import MonitoringResponden from "../views/Monitoring.vue";
import DashboardPetugas from "../views/petugas/DashboardPetugas.vue";
import BantuanPetugas from "../views/petugas/BantuanPetugas.vue";
import MonitoringPetugas from "../views/petugas/MonitoringPetugas.vue";
import LansiaPetugas from "../views/petugas/LansiaPetugas.vue";
const routes = [
    // Public
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { public: true },
    },

    {
        path: "/",
        redirect: () => {
            const role = authStore.user?.role;

            return role === "petugas" ? "/petugas/dashboard" : "/dashboard";
        },
    },

    // Shared (authenticated)
    {
        path: "/dashboard",
        component: Dashboard,
        meta: { auth: true },
    },

    {
        path: "/lansia",
        component: DataLansia,
        meta: { auth: true },
    },

    {
        path: "/penilaian",
        component: Penilaian,
        meta: { auth: true },
    },

    {
        path: "/hasil",
        component: HasilPrioritas,
        meta: { auth: true },
    },

    // Admin Only
    {
        path: "/kriteria",
        component: Kriteria,
        meta: {
            auth: true,
            role: "admin",
        },
    },

    {
        path: "/perhitungan",
        component: Perhitungan,
        meta: {
            auth: true,
            role: "admin",
        },
    },

    {
        path: "/bantuan",
        component: Bantuan,
        meta: {
            auth: true,
            role: "admin",
        },
    },

    {
        path: "/monitoring",
        component: Monitoring,
        meta: {
            auth: true,
            role: "admin",
        },
    },

    {
        path: "/admin/pengguna",
        component: KelolaPengguna,
        meta: {
            auth: true,
            role: "admin",
        },
    },

    {
        path: "/admin/laporan",
        component: LaporanAdmin,
        meta: {
            auth: true,
            role: "admin",
        },
    },
    // Petugas Only
    {
        path: "/petugas/dashboard",
        component: DashboardPetugas,
        meta: {
            auth: true,
            role: "petugas",
        },
    },
    {
        path: "/petugas/bantuan",
        component: BantuanPetugas,
        meta: {
            auth: true,
            role: "petugas",
        },
    },
    {
        path: "/petugas/monitoring",
        component: MonitoringPetugas,
        meta: {
            auth: true,
            role: "petugas",
        },
    },

    {
        path: "/petugas/lansia",
        component: LansiaPetugas,
        meta: {
            auth: true,
            role: "petugas",
        },
    },
    // Fallback
    {
        path: "/:pathMatch(.*)*",
        redirect: "/dashboard",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation Guard
router.beforeEach((to) => {
    const isLoggedIn = authStore.isLoggedIn();
    const userRole = authStore.user?.role;

    // Login page
    if (to.meta.public) {
        if (isLoggedIn) {
            return userRole === "petugas" ? "/petugas/dashboard" : "/dashboard";
        }

        return true;
    }

    // Protected routes
    if (to.meta.auth && !isLoggedIn) {
        return "/login";
    }

    // Role-based routes
    if (to.meta.role && to.meta.role !== userRole) {
        return userRole === "petugas" ? "/petugas/dashboard" : "/dashboard";
    }

    return true;
});

export default router;
