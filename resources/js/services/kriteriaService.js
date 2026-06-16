import api from "./api.js";

export const kriteriaService = {
    async getAll() {
        const response = await api.get("/kriteria");
        return response.data;
    },

    async create(payload) {
        const response = await api.post("/kriteria", payload);
        return response.data;
    },

    async update(id, payload) {
        const response = await api.put(`/kriteria/${id}`, payload);
        return response.data;
    },

    async remove(id) {
        const response = await api.delete(`/kriteria/${id}`);
        return response.data;
    },
};
