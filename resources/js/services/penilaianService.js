import api from "./api.js";

export const penilaianService = {
    async getAll() {
        const response = await api.get("/penilaian");
        return response.data;
    },

    async create(payload) {
        const response = await api.post("/penilaian", payload);
        return response.data;
    },

    async update(id, payload) {
        const response = await api.put(`/penilaian/${id}`, payload);
        return response.data;
    },

    async remove(id) {
        const response = await api.delete(`/penilaian/${id}`);
        return response.data;
    },
};
