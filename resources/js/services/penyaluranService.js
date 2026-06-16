import api from "./api.js";

export const penyaluranService = {
    async getAll() {
        const response = await api.get("/penyaluran");
        return response.data?.data || [];
    },
};
