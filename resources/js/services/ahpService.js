import api from "./api.js";

export const ahpService = {
    async hitung() {
        const response = await api.post("/ahp/hitung");
        return response.data;
    },

    async getBobot() {
        const response = await api.get("/ahp/bobot");
        return response.data;
    },

    async getKonsistensi() {
        const response = await api.get("/ahp/konsistensi");
        return response.data;
    },
};
