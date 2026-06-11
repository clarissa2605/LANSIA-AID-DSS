import axios from "axios";

const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
        Accept: "application/json",
    },
    timeout: 30000, // 30 second timeout
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

/**
 * Response interceptor to handle common errors
 */
api.interceptors.response.use(
    (response) => response,
    (error) => {
        const response = error.response;

        // Handle timeout
        if (error.code === "ECONNABORTED") {
            error.message =
                "Permintaan timeout. Server tidak merespons dalam waktu yang ditentukan.";
            error.isTimeout = true;
        }

        // Handle network errors
        if (!response) {
            error.message =
                "Gagal terhubung ke server. Periksa koneksi internet Anda.";
            error.isNetworkError = true;
            return Promise.reject(error);
        }

        // Handle common HTTP errors
        switch (response.status) {
            case 400:
                error.statusLabel = "Bad Request";
                break;
            case 401:
                error.statusLabel = "Unauthorized";
                error.message =
                    "Sesi Anda telah berakhir. Silakan login kembali.";
                // Optional: redirect to login
                localStorage.removeItem("token");
                break;
            case 403:
                error.statusLabel = "Forbidden";
                error.message =
                    "Anda tidak memiliki izin untuk melakukan tindakan ini.";
                break;
            case 404:
                error.statusLabel = "Not Found";
                error.message = "Data tidak ditemukan.";
                break;
            case 409:
                error.statusLabel = "Conflict";
                error.message =
                    response.data?.message ||
                    "Terjadi konflik dengan data yang ada.";
                break;
            case 422:
                error.statusLabel = "Unprocessable Entity";
                error.message =
                    response.data?.message ||
                    "Data tidak valid. Periksa kembali input Anda.";
                error.validationErrors = response.data?.errors || {};
                break;
            case 500:
                error.statusLabel = "Server Error";
                error.message =
                    "Terjadi kesalahan pada server. Silakan coba lagi nanti.";
                break;
            case 503:
                error.statusLabel = "Service Unavailable";
                error.message =
                    "Server sedang tidak tersedia. Silakan coba lagi nanti.";
                break;
        }

        return Promise.reject(error);
    },
);

export default api;
