import api from "./api.js";

export const pengajuanBantuanService = {
    async getAll() {
        const response = await api.get("/pengajuan-bantuan");
        return response.data?.data || [];
    },

    async create(payload) {
        const response = await api.post("/pengajuan-bantuan", payload);
        return response.data?.data || response.data;
    },

    async update(id, payload) {
        const response = await api.put(`/pengajuan-bantuan/${id}`, payload);
        return response.data?.data || response.data;
    },

    async remove(id) {
        const response = await api.delete(`/pengajuan-bantuan/${id}`);
        return response.data;
    },
};
