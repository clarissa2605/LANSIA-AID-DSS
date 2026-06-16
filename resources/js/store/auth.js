import { reactive } from "vue";
import axios from "axios";

axios.defaults.baseURL = "http://127.0.0.1:8000/api";

const savedUser = localStorage.getItem("user");
const savedToken = localStorage.getItem("token");

if (savedToken) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${savedToken}`;
}

export const authStore = reactive({
    user: savedUser ? JSON.parse(savedUser) : null,

    token: savedToken,

    async login(email, password) {
        const response = await axios.post("/login", {
            email,
            password,
        });

        this.token = response.data.token;
        this.user = response.data.user;

        localStorage.setItem("token", response.data.token);

        localStorage.setItem("user", JSON.stringify(response.data.user));

        axios.defaults.headers.common["Authorization"] =
            `Bearer ${response.data.token}`;

        return response.data.user;
    },

    async logout() {
        try {
            await axios.post("/logout");
        } catch (e) {}

        this.user = null;
        this.token = null;

        localStorage.removeItem("token");
        localStorage.removeItem("user");

        delete axios.defaults.headers.common.Authorization;
    },

    isAdmin() {
        return this.user?.role === "admin";
    },

    isPetugas() {
        return this.user?.role === "petugas";
    },

    isLoggedIn() {
        return !!this.token;
    },
});
