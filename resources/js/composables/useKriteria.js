import { ref } from "vue";
import { kriteriaService } from "../services/kriteriaService.js";

export function useKriteria() {
    const showModal = ref(false);
    const loading = ref(false);
    const error = ref("");

    const kriteria = ref([]);

    const form = ref({
        kode: "",
        nama: "",
        atribut: "benefit",
    });

    const isEdit = ref(false);
    const selectedId = ref(null);

    const skalaAhp = [
        { val: 1, label: "Sama penting" },
        { val: 3, label: "Sedikit lebih penting" },
        { val: 5, label: "Lebih penting" },
        { val: 7, label: "Sangat penting" },
        { val: 9, label: "Mutlak lebih penting" },
    ];

    const matrix = ref([]);

    async function loadKriteria() {
        loading.value = true;
        error.value = "";

        try {
            const response = await kriteriaService.getAll();

            kriteria.value = response.map(mapKriteria);

            matrix.value = createMatrix(kriteria.value.length);
        } catch (err) {
            error.value =
                err?.response?.data?.message || "Gagal memuat data kriteria.";

            console.error(err);
        } finally {
            loading.value = false;
        }
    }

    function mapKriteria(item) {
        return {
            id: item.id,
            kode: item.kode,
            nama: item.nama,
            deskripsi: item.kode,
            jenis: item.atribut === "cost" ? "Cost" : "Benefit",
            atribut: item.atribut,
            bobot: Number(item.bobot || 0).toFixed(4),
        };
    }

    function createMatrix(size) {
        return Array.from({ length: size }, (_, row) =>
            Array.from({ length: size }, (_, col) => (row === col ? 1 : "")),
        );
    }

    function tambahKriteria() {
        isEdit.value = false;
        selectedId.value = null;

        form.value = {
            kode: "",
            nama: "",
            atribut: "benefit",
        };

        showModal.value = true;
    }

    function editKriteria(item) {
        isEdit.value = true;
        selectedId.value = item.id;

        form.value = {
            kode: item.kode,
            nama: item.nama,
            atribut: item.atribut,
        };

        showModal.value = true;
    }

    async function simpanKriteria() {
        try {
            if (!form.value.kode || !form.value.nama) {
                alert("Lengkapi data.");
                return;
            }

            const payload = {
                kode: form.value.kode,
                nama: form.value.nama,
                atribut: form.value.atribut,
            };

            if (isEdit.value) {
                await kriteriaService.update(selectedId.value, payload);
            } else {
                await kriteriaService.create(payload);
            }

            await loadKriteria();

            form.value = {
                kode: "",
                nama: "",
                atribut: "benefit",
            };

            isEdit.value = false;
            selectedId.value = null;

            showModal.value = false;

            showModal.value = false;
        } catch (error) {
            console.error(error);

            if (error?.response?.data?.errors) {
                alert(
                    Object.values(error.response.data.errors).flat().join("\n"),
                );
            } else {
                alert(error?.response?.data?.message || "Gagal menyimpan.");
            }
        }
    }

    async function hapusKriteria(item) {
        if (!confirm(`Hapus kriteria ${item.nama}?`)) {
            return;
        }

        try {
            await kriteriaService.remove(item.id);

            await loadKriteria();
        } catch (error) {
            console.error(error);

            alert(error?.response?.data?.message || "Gagal menghapus.");
        }
    }

    return {
        showModal,
        loading,
        error,

        kriteria,
        matrix,
        skalaAhp,

        form,
        isEdit,
        selectedId,

        loadKriteria,
        tambahKriteria,
        editKriteria,
        simpanKriteria,
        hapusKriteria,
    };
}
