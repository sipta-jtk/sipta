$(document).ready(function () {
    let jenisForm = $('#jenisForm');
    let namaProdi = $('#namaProdi');
    let jenisTAContainer = $('#jenisTAContainer');

    function toggleTables() {
        let jenis = jenisForm.val();
        $('#tablePenilaian').toggle(jenis === 'Penilaian');
        $('#tableFeedback').toggle(jenis === 'Feedback');
    }

    function toggleJenisTA() {
        const selectedProdiText = namaProdi.find('option:selected').text().trim();
    
        if (selectedProdiText === 'D4-Teknik Informatika') {
            // Show Jenis TA dropdown
            jenisTAContainer.show();
            $('#jenisTA').prop('required', true);
    
            // Remove any existing hidden field for jenisTA
            $('input[name="jenisTA"][type="hidden"]').remove();
        } else if (selectedProdiText === 'D3-Teknik Informatika') {
            // Hide Jenis TA dropdown and set default value
            jenisTAContainer.hide();
            $('#jenisTA').prop('required', false);
    
            // Remove existing hidden field if any
            $('input[name="jenisTA"][type="hidden"]').remove();
    
            // Add hidden field with default value
            $('<input type="hidden" name="jenisTA" value="Pengembangan">')
                .insertAfter(namaProdi.closest('.form-group'));
        } else {
            // For other programs, hide both
            jenisTAContainer.hide();
            $('#jenisTA').prop('required', false);
            $('input[name="jenisTA"][type="hidden"]').remove();
        }
    }

    // Inisialisasi tampilan saat halaman dimuat
    toggleTables();
    toggleJenisTA();

    // Event listener untuk perubahan pilihan
    jenisForm.change(toggleTables);
    namaProdi.change(toggleJenisTA);

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

    // Event listener untuk menambah baris
    $("#addRow").click(function() {
        if ($("#jenisForm").val() === "Feedback") {
            $("#aspekFeedbackTable").append(`
                <tr>
                    <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </td>
                </tr>
            `);
        } else {
            $("#aspekPenilaianTable").append(`
                <tr>
                    <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                    <td><input type="number" class="form-control bobot-kriteria" name="bobot_kriteria[]" required></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </td>
                </tr>
            `);
        }
        validateTotalBobot();
    });

    // Event listener untuk menghapus baris
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        validateTotalBobot(); 
    });

    function initBobotInputs() {
        $('input[name="bobot_kriteria[]"]').addClass('bobot-kriteria');
    }

    // Validasi total bobot
    function validateTotalBobot() {
        initBobotInputs();
        
        let totalBobot = 0;

        // Hitung total bobot dari semua input
        $(".bobot-kriteria").each(function () {
            let value = parseFloat($(this).val());
            if (!isNaN(value)) {
                totalBobot += value;
            }
        });

        // Menampilkan pesan error jika total bobot tidak 100
        if (totalBobot !== 100) {
            $("#bobotError")
                .removeClass("d-none")
                .text(`Total bobot harus 100%. Saat ini: ${totalBobot}%`);
        } else {
            $("#bobotError").addClass("d-none").text("");
        }

        $(".bobot-summary").text(`Total bobot harus 100%. Saat ini: ${totalBobot}%`);
        
        return totalBobot;
    }

    // Event listener untuk memeriksa total bobot saat nilai diubah
    $(document).on('input', '.bobot-kriteria', function () {
        validateTotalBobot();
    });

    // Event listener untuk value yang sudah ada saat halaman dimuat
    initBobotInputs();
    validateTotalBobot();

    $('#aspekFormulirForm').on('submit', function(e) {
        let totalBobot = validateTotalBobot();

        if (totalBobot !== 100) {
            e.preventDefault();
            $("#notification")
                .removeClass("d-none")
                .find("#notificationMessage")
                .text("Total Bobot Harus 100%");

            setTimeout(function () {
                $("#notification").addClass("d-none");
            }, 3000);
        }
    });

    // Memicu button submit
    $('.btn-primary').on('click', function() {
        console.log('Button clicked');
        $('#aspekFormulirForm').submit();
    });
});
