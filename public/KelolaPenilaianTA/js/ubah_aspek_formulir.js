$(document).ready(function () {
    function updateBobotSummary() {
        let totalBobot = 0;
        $("input[name='bobot_kriteria[]']").each(function () {
            let bobot = parseFloat($(this).val()) || 0;
            totalBobot += bobot;
        });

        $(".bobot-summary").text(`Total bobot harus 100%. Saat ini: ${totalBobot}%`);
        return totalBobot;
    }

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

    // Update total bobot saat nilai bobot diubah
    $(document).on("input", "input[name='bobot_kriteria[]']", function () {
        if ($("#jenisForm").val() === "penilaian") {
            updateBobotSummary();
        }
    });

    // Form tidak bisa dikirim jika total bobot tidak 100% (hanya untuk penilaian)
    $('#aspekForm').on('submit', function (e) {
        const jenisForm = $("#jenisForm").val();
        if (jenisForm === "penilaian") {
            let totalBobot = updateBobotSummary();
            if (totalBobot !== 100) {
                e.preventDefault();
                $("#notificationMessage").text("Total bobot harus 100%");
                $("#notification").removeClass("d-none");

                setTimeout(function () {
                    $("#notification").addClass("d-none");
                }, 3000);
            }
        }
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
    });

    // Hapus baris dari tabel Aspek Penilaian
    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
        if ($("#jenisForm").val() === "penilaian") {
            updateBobotSummary();
        }
    });

    // Hapus baris dari tabel Aspek Feedback
    $(document).on("click", ".remove-feedback-row", function () {
        $(this).closest("tr").remove();
    });
});