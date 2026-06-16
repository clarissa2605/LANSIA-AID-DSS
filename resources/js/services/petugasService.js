import api from "./api.js";

export const petugasService = {
    async dashboard() {
        const response = await api.get("/petugas/dashboard");

        return response.data;
    },

    async tugas() {
        const response = await api.get("/petugas/tugas");

        return response.data;
    },

    async monitoring() {
        const response = await api.get("/petugas/monitoring");

        return response.data;
    },

    async riwayat() {
        const response = await api.get("/petugas/riwayat");

        return response.data;
    },

    // ==========================
    // BANTUAN / PENYALURAN
    // ==========================

    async bantuan() {
        const response = await api.get("/pengajuan-bantuan");

        return response.data.data;
    },

    async verifikasi(id) {
        const response = await api.patch(`/pengajuan-bantuan/${id}/verifikasi`);

        return response.data;
    },

    async salurkan(id) {
        const response = await api.patch(`/pengajuan-bantuan/${id}/salurkan`);

        return response.data;
    },

    async tolak(id) {
        const response = await api.patch(`/pengajuan-bantuan/${id}/tolak`);

        return response.data;
    },
};
