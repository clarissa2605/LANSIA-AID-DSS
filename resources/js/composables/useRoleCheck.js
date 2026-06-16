/**
 * Role-based UI rendering utilities
 * Provides consistent role checking across components
 */

import { useAuth } from "../auth.js";

export function useRoleCheck() {
    const auth = useAuth();

    /**
     * Check if current user is admin
     * @returns {boolean}
     */
    function isAdmin() {
        return auth.isAdmin?.();
    }

    /**
     * Check if current user is petugas/responden
     * @returns {boolean}
     */
    function isPetugas() {
        return auth.isResponden?.();
    }

    /**
     * Check if current user can perform admin-only action
     * @returns {boolean}
     */
    function canManageCriteria() {
        return isAdmin();
    }

    /**
     * Check if current user can calculate AHP
     * @returns {boolean}
     */
    function canCalculateAhp() {
        return isAdmin();
    }

    /**
     * Check if current user can manage users
     * @returns {boolean}
     */
    function canManageUsers() {
        return isAdmin();
    }

    /**
     * Check if current user can create lansia
     * @returns {boolean}
     */
    function canCreateLansia() {
        return true; // Both admin and petugas
    }

    /**
     * Check if current user can create penilaian
     * @returns {boolean}
     */
    function canCreatePenilaian() {
        return true; // Both admin and petugas
    }

    /**
     * Check if current user can approve requests
     * @returns {boolean}
     */
    function canApproveRequests() {
        return isAdmin();
    }

    /**
     * Check if current user can process requests
     * @returns {boolean}
     */
    function canProcessRequests() {
        return true; // Both admin and petugas
    }

    /**
     * Check if current user can view admin report
     * @returns {boolean}
     */
    function canViewAdminReport() {
        return isAdmin();
    }

    /**
     * Get visible navigation items based on role
     * @returns {Array} Navigation items
     */
    function getVisibleNavItems() {
        const baseItems = [
            { label: "Dashboard", icon: "LayoutDashboard", to: "/dashboard" },
            { label: "Data Lansia", icon: "Users", to: "/lansia" },
            { label: "Penilaian", icon: "ClipboardEdit", to: "/penilaian" },
            { label: "Hasil", icon: "BarChart3", to: "/hasil" },
            { label: "Penyaluran", icon: "Package", to: "/bantuan" },
            { label: "Monitoring", icon: "Activity", to: "/monitoring" },
        ];

        const adminItems = [
            { label: "Kriteria", icon: "SlidersHorizontal", to: "/kriteria" },
            { label: "Perhitungan", icon: "Calculator", to: "/perhitungan" },
            { label: "Pengguna", icon: "ShieldCheck", to: "/admin/pengguna" },
            { label: "Laporan", icon: "FileBarChart", to: "/admin/laporan" },
        ];

        if (isAdmin()) {
            return baseItems.concat(adminItems);
        }

        return baseItems;
    }

    return {
        isAdmin,
        isPetugas,
        canManageCriteria,
        canCalculateAhp,
        canManageUsers,
        canCreateLansia,
        canCreatePenilaian,
        canApproveRequests,
        canProcessRequests,
        canViewAdminReport,
        getVisibleNavItems,
    };
}
