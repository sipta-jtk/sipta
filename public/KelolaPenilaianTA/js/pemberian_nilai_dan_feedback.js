document.addEventListener("DOMContentLoaded", function () {
    const tanggalInput = document.getElementById("tanggal_catatan");
    const hariPerbaikan = document.getElementById("hari_perbaikan");
    const tanggalPerbaikan = document.getElementById("tanggal_perbaikan");

    // Fungsi untuk mengubah tanggal menjadi format hari & tanggal
    function formatTanggal(dateString) {
        if (!dateString) return { hari: "________", tanggal: "________" };

        const hariList = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const bulanList = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        let date = new Date(dateString);
        let hari = hariList[date.getDay()];
        let tanggal = date.getDate();
        let bulan = bulanList[date.getMonth()];
        let tahun = date.getFullYear();

        return { hari, tanggal: `${tanggal} ${bulan} ${tahun}` };
    }

    // Event listener ketika user memilih tanggal
    tanggalInput.addEventListener("change", function () {
        let hasilFormat = formatTanggal(this.value);
        hariPerbaikan.textContent = hasilFormat.hari;
        tanggalPerbaikan.textContent = hasilFormat.tanggal;
    });
});