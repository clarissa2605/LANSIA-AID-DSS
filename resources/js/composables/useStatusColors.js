/**
 * Centralized status and badge color mapping
 * Ensures consistency across entire application
 */

export function useStatusColors() {
    // Status badge colors (status of pengajuan/lansia/result)
    const statusBadgeMap = {
        // SUCCESS / COMPLETED (Green)
        disalurkan: "bg-success",
        aktif: "bg-success",
        selesai: "bg-success",

        // IN PROGRESS (Blue)
        diproses: "bg-primary",
        sedang_diverifikasi: "bg-primary",
        "sedang diverifikasi": "bg-primary",

        // WAITING (Yellow)
        pending: "bg-warning text-dark",
        menunggu_persetujuan: "bg-warning text-dark",
        "menunggu persetujuan": "bg-warning text-dark",

        // REJECTED / CRITICAL (Red)
        ditolak: "bg-danger",
        tidak_valid: "bg-danger",
        "tidak valid": "bg-danger",
        data_bermasalah: "bg-danger",
        "data bermasalah": "bg-danger",

        // NEUTRAL (Gray)
        draft: "bg-secondary",
        belum_ada_data: "bg-secondary",
        "belum ada data": "bg-secondary",
    };

    // Priority badges (Prioritas Utama, Diprioritaskan, Cukup, Rendah)
    const priorityBadgeMap = {
        "prioritas utama": "bg-danger",
        prioritas_utama: "bg-danger",
        diprioritaskan: "bg-warning text-dark",
        cukup: "bg-primary",
        rendah: "bg-secondary",
    };

    // Urgency badges (Tinggi, Sedang, Rendah)
    const urgencyBadgeMap = {
        tinggi: "bg-danger",
        sedang: "bg-warning text-dark",
        rendah: "bg-success",
    };

    /**
     * Get status badge class
     * @param {string} status - Status value
     * @returns {string} Bootstrap badge class
     */
    function getStatusBadgeClass(status) {
        if (!status) return "bg-secondary";
        const normalized = status.toLowerCase().trim();
        return statusBadgeMap[normalized] || "bg-secondary";
    }

    /**
     * Get priority badge class
     * @param {string} priority - Priority value
     * @returns {string} Bootstrap badge class
     */
    function getPriorityBadgeClass(priority) {
        if (!priority) return "bg-secondary";
        const normalized = priority.toLowerCase().trim();
        return priorityBadgeMap[normalized] || "bg-secondary";
    }

    /**
     * Get urgency badge class
     * @param {string} urgency - Urgency value
     * @returns {string} Bootstrap badge class
     */
    function getUrgencyBadgeClass(urgency) {
        if (!urgency) return "bg-secondary";
        const normalized = urgency.toLowerCase().trim();
        return urgencyBadgeMap[normalized] || "bg-secondary";
    }

    /**
     * Format status display text
     * @param {string} status - Status value
     * @returns {string} Formatted display text
     */
    function formatStatusDisplay(status) {
        if (!status) return "-";
        return status
            .replace(/_/g, " ")
            .replace(/\b\w/g, (c) => c.toUpperCase());
    }

    /**
     * Check if status is terminal/complete
     * @param {string} status - Status value
     * @returns {boolean}
     */
    function isTerminalStatus(status) {
        const terminal = ["disalurkan", "ditolak", "selesai"];
        return terminal.includes(status?.toLowerCase()?.trim());
    }

    /**
     * Get next allowed statuses for state machine
     * @param {string} currentStatus - Current status
     * @returns {string[]} Allowed next statuses
     */
    function getNextStatuses(currentStatus) {
        const transitions = {
            pending: ["diproses", "ditolak"],
            diproses: ["disalurkan", "ditolak"],
            disalurkan: [], // Terminal
            ditolak: [], // Terminal
            selesai: [], // Terminal
        };

        const normalized = currentStatus?.toLowerCase()?.trim();
        return transitions[normalized] || [];
    }

    return {
        statusBadgeMap,
        priorityBadgeMap,
        urgencyBadgeMap,
        getStatusBadgeClass,
        getPriorityBadgeClass,
        getUrgencyBadgeClass,
        formatStatusDisplay,
        isTerminalStatus,
        getNextStatuses,
    };
}
