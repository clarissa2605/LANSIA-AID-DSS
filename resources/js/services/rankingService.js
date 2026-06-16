import api from "./api.js";

export const rankingService = {
    async getAll() {
        const response = await api.get("/ranking");
        return response.data;
    },

    async hitung() {
        const response = await api.post("/ranking/hitung");
        return response.data?.data || [];
    },
};
