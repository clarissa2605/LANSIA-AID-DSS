import api from "./api.js";

export const dashboardService = {
    async getDashboard() {
        const response = await api.get("/dashboard");
        return response.data;
    },

    async getStatistik() {
        const response = await api.get("/dashboard/statistik");
        return response.data;
    },
};
