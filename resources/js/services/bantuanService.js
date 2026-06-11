import api from "./api.js";

export const bantuanService = {
    async getPenerimaBantuan(limit = 10) {
        const response = await api.get("/penerima-bantuan", {
            params: { limit },
        });

        return response.data?.data || [];
    },

    async createPenerimaBantuan(payload) {
        const response = await api.post("/penerima-bantuan", payload);
        return response.data;
    },

    async updatePenerimaBantuanStatus(id, payload) {
        const response = await api.patch(`/penerima-bantuan/${id}`, payload);
        return response.data;
    },
};
