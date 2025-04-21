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

    $(document).ready(function() {
        // Menginisialisasi popover untuk elemen yang sudah ada
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            placement: 'top',
            html: true
        });

        // Event delegation untuk elemen dinamis
        $(document).on('mouseenter', '[data-toggle="popover"]', function () {
            $(this).popover('show');
        }).on('mouseleave', '[data-toggle="popover"]', function () {
            $(this).popover('hide');
        });
    });

    function loadPreview(url) {
        let fileId = extractDriveFileId(url);
        if (fileId) {
            let embedUrl = `https://drive.google.com/file/d/${fileId}/preview`;
            document.getElementById('previewFrame').src = embedUrl;
        } else {
            alert("Format link tidak valid!");
        }
    }

    function extractDriveFileId(url) {
        let match = url.match(/[-\w]{25,}/);
        return match ? match[0] : null;
    }
});