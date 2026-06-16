import { ref } from "vue";
import { pengajuanBantuanService } from "../services/pengajuanBantuanService.js";

export function usePengajuanBantuan() {
    const loading = ref(false);
    const error = ref("");
    const pengajuan = ref([]);

    async function loadPengajuan() {
        loading.value = true;
        error.value = "";

        try {
            pengajuan.value = await pengajuanBantuanService.getAll();
        } catch (err) {
            error.value =
                err?.response?.data?.message ||
                "Gagal memuat pengajuan bantuan";

            console.error(error.value);
        } finally {
            loading.value = false;
        }
    }

    async function createPengajuan(payload) {
        loading.value = true;
        error.value = "";

        try {
            const created = await pengajuanBantuanService.create(payload);

            await loadPengajuan();

            return created;
        } catch (err) {
            error.value =
                err?.response?.data?.message ||
                "Gagal membuat pengajuan bantuan";

            console.error(error.value);

            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function editPengajuan(id, payload) {
        loading.value = true;
        error.value = "";

        try {
            const updated = await pengajuanBantuanService.update(id, payload);

            await loadPengajuan();

            return updated;
        } catch (err) {
            error.value =
                err?.response?.data?.message ||
                "Gagal memperbarui pengajuan bantuan";

            console.error(error.value);

            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateStatus(id, payload) {
        loading.value = true;
        error.value = "";

        try {
            const updated = await pengajuanBantuanService.update(id, payload);

            await loadPengajuan();

            return updated;
        } catch (err) {
            error.value =
                err?.response?.data?.message ||
                "Gagal memperbarui status pengajuan";

            console.error(error.value);

            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deletePengajuan(id) {
        loading.value = true;
        error.value = "";

        try {
            const deleted = await pengajuanBantuanService.remove(id);

            await loadPengajuan();

            return deleted;
        } catch (err) {
            error.value =
                err?.response?.data?.message ||
                "Gagal menghapus pengajuan bantuan";

            console.error(error.value);

            throw err;
        } finally {
            loading.value = false;
        }
    }

    return {
        loading,
        error,

        pengajuan,

        loadPengajuan,
        createPengajuan,
        editPengajuan,
        updateStatus,
        deletePengajuan,
    };
}
