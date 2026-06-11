import { computed, ref } from "vue";
import { lansiaService } from "../services/lansiaService.js";

export function useLansia() {
    const showModal = ref(false);
    const isEdit = ref(false);
    const selectedId = ref(null);

    const search = ref("");

    const form = ref({
        nik: "",
        nama: "",
        umur: "",
        jenis_kelamin: "L",
        alamat: "",
        kecamatan: "",
        status_tinggal: "tinggal_sendiri",
        kondisi_kesehatan: "sehat",
        kondisi_rumah: "rumah_layak",
        kategori_penghasilan: "tidak_memiliki_penghasilan",
    });

    const loading = ref(false);
    const error = ref("");
    const lansiaData = ref([]);

    const filtered = computed(() =>
        lansiaData.value.filter((l) =>
            (l.nama || "").toLowerCase().includes(search.value.toLowerCase()),
        ),
    );

    async function loadLansia() {
        loading.value = true;
        error.value = "";

        try {
            const response = await lansiaService.getAll();

            lansiaData.value = response.map(mapLansia);
        } catch (err) {
            error.value =
                err?.response?.data?.message || "Gagal memuat data lansia.";

            console.error(error.value);
        } finally {
            loading.value = false;
        }
    }

    function mapLansia(item) {
        return {
            id: item.id,
            nik: item.nik,
            nama: item.nama,
            umur: item.umur,
            jenis_kelamin: item.jenis_kelamin,
            alamat: item.alamat,
            kecamatan: item.kecamatan,
            status_tinggal: item.status_tinggal,
            kondisi_kesehatan: item.kondisi_kesehatan,
            kondisi_rumah: item.kondisi_rumah,
            kategori_penghasilan: item.kategori_penghasilan,

            status: item.status_tinggal,
            ekonomi: item.kategori_penghasilan,
        };
    }

    function editData(item) {
        isEdit.value = true;
        selectedId.value = item.id;

        const original = lansiaData.value.find((l) => l.id === item.id);

        if (!original) return;

        form.value = {
            nik: original.nik,
            nama: original.nama,
            umur: original.umur,
            jenis_kelamin: original.jenis_kelamin,
            alamat: original.alamat,
            kecamatan: original.kecamatan,
            status_tinggal: original.status_tinggal,
            kondisi_kesehatan: original.kondisi_kesehatan,
            kondisi_rumah: original.kondisi_rumah,
            kategori_penghasilan: original.kategori_penghasilan,
        };

        showModal.value = true;
    }

    async function hapus(item) {
        // Show warning if lansia might have active requests
        if (
            !confirm(
                `Hapus data ${item.nama}?\n\nPerhatian: Pastikan lansia tidak memiliki pengajuan bantuan yang aktif.`,
            )
        ) {
            return;
        }

        try {
            await lansiaService.remove(item.id);

            await loadLansia();

            // Show success message
            alert(`Data ${item.nama} berhasil dihapus.`);
        } catch (error) {
            console.error(error);

            // Handle specific error cases
            if (error?.response?.status === 409) {
                // Conflict - likely has active pengajuan
                alert(
                    error?.response?.data?.message ||
                        `${item.nama} tidak dapat dihapus karena masih memiliki pengajuan bantuan yang aktif.\n\nSelesaikan semua pengajuan terlebih dahulu.`,
                );
            } else if (error?.response?.status === 500) {
                alert(
                    "Terjadi kesalahan pada server. Silakan coba lagi nanti.",
                );
            } else {
                alert(error?.response?.data?.message || "Gagal menghapus data");
            }
        }
    }

    async function simpan() {
        if (
            !form.value.nik ||
            !form.value.nama ||
            !form.value.umur ||
            !form.value.alamat ||
            !form.value.kecamatan
        ) {
            alert("Lengkapi semua data terlebih dahulu");
            return;
        }

        try {
            const payload = {
                nik: form.value.nik,
                nama: form.value.nama,
                umur: Number(form.value.umur),
                jenis_kelamin: form.value.jenis_kelamin,
                alamat: form.value.alamat,
                kecamatan: form.value.kecamatan,
                status_tinggal: form.value.status_tinggal,
                kondisi_kesehatan: form.value.kondisi_kesehatan,
                kondisi_rumah: form.value.kondisi_rumah,
                kategori_penghasilan: form.value.kategori_penghasilan,
            };

            if (isEdit.value) {
                await lansiaService.update(selectedId.value, payload);
            } else {
                await lansiaService.create(payload);
            }

            await loadLansia();

            resetForm();

            isEdit.value = false;
            selectedId.value = null;
            showModal.value = false;
        } catch (error) {
            console.error(error);

            if (error?.response?.data?.errors) {
                const errors = Object.values(error.response.data.errors)
                    .flat()
                    .join("\n");

                alert(errors);
            } else {
                alert(error?.response?.data?.message || "Gagal menyimpan data");
            }
        }
    }

    function resetForm() {
        form.value = {
            nik: "",
            nama: "",
            umur: "",
            jenis_kelamin: "L",
            alamat: "",
            kecamatan: "",
            status_tinggal: "tinggal_sendiri",
            kondisi_kesehatan: "sehat",
            kondisi_rumah: "rumah_layak",
            kategori_penghasilan: "tidak_memiliki_penghasilan",
        };
    }

    function tambahData() {
        isEdit.value = false;
        selectedId.value = null;

        resetForm();

        showModal.value = true;
    }

    return {
        showModal,
        isEdit,
        selectedId,

        search,
        form,

        loading,
        error,

        lansiaData,
        filtered,

        loadLansia,
        editData,
        hapus,
        simpan,
        tambahData,
    };
}
