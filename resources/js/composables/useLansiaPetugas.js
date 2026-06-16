import { ref, reactive, computed } from "vue";
import { lansiaService } from "../services/lansiaService";

export function useLansiaPetugas() {
    const loading = ref(false);
    const error = ref("");

    const showModal = ref(false);
    const search = ref("");

    const lansiaData = ref([]);

    const form = reactive({
        nik: "",
        nama: "",
        umur: "",
        jenis_kelamin: "L",
        alamat: "",
        kecamatan: "",
        status_tinggal: "tinggal_sendiri",
        kondisi_kesehatan: "sehat",
        kondisi_rumah: "rumah_layak",
        kategori_penghasilan: "penghasilan_rendah",
    });

    const filtered = computed(() => {
        if (!search.value) {
            return lansiaData.value;
        }

        return lansiaData.value.filter((item) =>
            item.nama?.toLowerCase().includes(search.value.toLowerCase()),
        );
    });

    async function loadLansia() {
        loading.value = true;
        error.value = "";

        try {
            lansiaData.value = await lansiaService.getAll();
        } catch (err) {
            console.error(err);

            error.value =
                err?.response?.data?.message || "Gagal memuat data lansia";
        } finally {
            loading.value = false;
        }
    }

    function resetForm() {
        form.nik = "";
        form.nama = "";
        form.umur = "";
        form.jenis_kelamin = "L";
        form.alamat = "";
        form.kecamatan = "";
        form.status_tinggal = "tinggal_sendiri";
        form.kondisi_kesehatan = "sehat";
        form.kondisi_rumah = "rumah_layak";
        form.kategori_penghasilan = "penghasilan_rendah";
    }

    function tambahData() {
        resetForm();
        showModal.value = true;
    }

    async function simpan() {
        loading.value = true;
        error.value = "";

        try {
            await lansiaService.create({
                nik: form.nik,
                nama: form.nama,
                umur: Number(form.umur),
                jenis_kelamin: form.jenis_kelamin,
                alamat: form.alamat,
                kecamatan: form.kecamatan,
                status_tinggal: form.status_tinggal,
                kondisi_kesehatan: form.kondisi_kesehatan,
                kondisi_rumah: form.kondisi_rumah,
                kategori_penghasilan: form.kategori_penghasilan,
            });

            showModal.value = false;

            await loadLansia();
        } catch (err) {
            console.error(err);

            error.value =
                err?.response?.data?.message || "Gagal menyimpan data lansia";
        } finally {
            loading.value = false;
        }
    }

    return {
        loading,
        error,

        showModal,
        search,

        form,
        lansiaData,
        filtered,

        loadLansia,
        tambahData,
        simpan,
    };
}
