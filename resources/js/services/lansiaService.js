import api from "./api.js";

export const lansiaService = {
    async getAll() {
        const response = await api.get("/lansia");
        return response.data;
    },

    async getById(id) {
        const response = await api.get(`/lansia/${id}`);
        return response.data;
    },

    async create(payload) {
        const response = await api.post("/lansia", payload);
        return response.data;
    },

    async update(id, payload) {
        const response = await api.put(`/lansia/${id}`, payload);
        return response.data;
    },

    async remove(id) {
        const response = await api.delete(`/lansia/${id}`);
        return response.data;
    },
};
