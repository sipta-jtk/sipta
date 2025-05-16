$(document).ready(function () {
    function validateTotalBobot() {
        let totalBobot = 0;

        // Hitung total bobot dari semua input
        $("input[name='bobot_kriteria[]']").each(function () {
            let value = parseFloat($(this).val());
            if (!isNaN(value)) {
                totalBobot += value;
            }
        });

        const bobotSummary = $(".bobot-summary");
        if (totalBobot === 100) {
            bobotSummary
                .removeClass("alert-warning")
                .addClass("alert-info")
                .text(`Total bobot harus 100%. Saat ini: ${totalBobot}%`);
        } else {
            bobotSummary
                .removeClass("alert-info")
                .addClass("alert-warning")
                .text(`Total bobot harus 100%. Saat ini: ${totalBobot}%`);
        }

        return totalBobot;
    }

    validateTotalBobot();

    function setMinDate() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        $('#tanggalTenggat').attr('min', minDate);
    }

    // Panggil fungsi untuk mengatur tanggal minimum saat halaman dimuat
    setMinDate();

    function updateRowStriping() {
        $("#aspekPenilaianTable tr, #aspekFeedbackTable tr").each(function (index) {
            if (index % 2 === 0) {
                $(this).css("background-color", "#ffffff"); // Warna putih untuk baris ganjil
            } else {
                $(this).css("background-color", "#f8f9fa"); // Warna abu untuk baris genap
            }
        });
    }

    // Panggil fungsi untuk menghitung total bobot saat halaman pertama kali dimuat
    if ($("#jenisForm").val() === "penilaian") {
        validateTotalBobot();
    }

    // Update total bobot saat nilai bobot diubah
    $(document).on("input", "input[name='bobot_kriteria[]']", function () {
        if ($("#jenisForm").val() === "penilaian") {
            validateTotalBobot();
        }
    });

    // Validasi Form sebelum submit
    $('#aspekForm').on('submit', function (e) {
        e.preventDefault();

        let valid = true;

        $("input[required], select[required], textarea[required]").each(function () {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });

        if (!valid) {
            Swal.fire({
                icon: "error",
                title: "Terdapat Field Kosong",
                text: "Mohon isi semua field yang diperlukan.",
            });
            return false;
        }

        const jenisForm = $("#jenisForm").val();
        if (jenisForm === "penilaian") {
            let totalBobot = validateTotalBobot();
            if (totalBobot !== 100) {
                Swal.fire({
                    icon: "error",
                    title: "Total Bobot Tidak Valid",
                    text: `Total bobot harus 100%`,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                return false;
            }
        }

        Swal.fire({
            title: "Konfirmasi Simpan",
            text: "Apakah Anda yakin ingin menyimpan perubahan aspek penilaian ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#aspekForm')[0].submit();
            }
        });
    });

    // Menambahkan baris baru di tabel Aspek Penilaian
    $("#addRow").on("click", function () {
        const jenisForm = $("#jenisForm").val();

        if (jenisForm === "penilaian") {
            const newRow = `
            <tr>
                <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>`;
            $("#aspekPenilaianTable").append(newRow);
            updateRowStriping();
        }
    });

    // Tambah baris baru di tabel Aspek Feedback
    $("#addFeedbackRow").on("click", function () {
        const newFeedbackRow = `
        <tr>
            <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-feedback-row">
                    <i class="fa-solid fa-minus"></i>
                </button>
            </td>
        </tr>`;
        $("#aspekFeedbackTable").append(newFeedbackRow);
        updateRowStriping();
    });

    // Hapus baris dari tabel Aspek Penilaian
    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
        if ($("#jenisForm").val() === "penilaian") {
            validateTotalBobot();
        }
        updateRowStriping();
    });

    // Hapus baris dari tabel Aspek Feedback
    $(document).on("click", ".remove-feedback-row", function () {
        $(this).closest("tr").remove();
        updateRowStriping();
    });

    updateRowStriping();
});