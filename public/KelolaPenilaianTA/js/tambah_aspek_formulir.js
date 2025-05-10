$(document).ready(function () {
    let jenisForm = $('#jenisForm');
    let namaProdi = $('#namaProdi');
    let jenisTAContainer = $('#jenisTAContainer');

    if (jenisTAContainer.length === 0) {
        $('<div class="col-md-6" id="jenisTAContainer">\
            <div class="form-group">\
                <label for="jenisTA">Jenis TA</label>\
                <select class="form-control" name="jenisTA" id="jenisTA" required>\
                    <option value="" disabled selected>-- Pilih Jenis TA --</option>\
                    <option value="Penelitian">Penelitian</option>\
                    <option value="Pengembangan">Pengembangan</option>\
                </select>\
            </div>\
        </div>').insertAfter($('#namaProdi').closest('.col-md-6'));
        
        jenisTAContainer = $('#jenisTAContainer');
    }
    
    jenisTAContainer.hide();

    function toggleTables() {
        let jenis = jenisForm.val();
        console.log("Jenis formulir diubah ke:", jenis);
        
        $('#tablePenilaian').toggle(jenis === 'Penilaian');
        $('#tableFeedback').toggle(jenis === 'Feedback');
        $(".bobot-summary").closest('tr').toggle(jenis === 'Penilaian');
        $("#notification").addClass("d-none");
    }

    function toggleJenisTA() {
        const selectedProdiId = namaProdi.val();
        const selectedProdiText = namaProdi.find('option:selected').text().trim();
        
        if (selectedProdiText.includes('D4-Teknik Informatika') || selectedProdiId === '2') {
            jenisTAContainer.show();
            $('#jenisTA').prop('required', true);
            $('input[name="jenisTA"][type="hidden"]').remove();
        } else if (selectedProdiText.includes('D3-Teknik Informatika') || selectedProdiId === '1') {
            jenisTAContainer.hide();
            $('#jenisTA').prop('required', false);
            $('input[name="jenisTA"][type="hidden"]').remove();
            $('<input type="hidden" name="jenisTA" value="Pengembangan">')
                .insertAfter(namaProdi.closest('.form-group'));
        } else {
            jenisTAContainer.hide();
            $('#jenisTA').prop('required', false);
            $('input[name="jenisTA"][type="hidden"]').remove();
        }
    }

    toggleTables();
    toggleJenisTA();

    jenisForm.change(toggleTables);
    namaProdi.change(function() {
        console.log("Prodi changed to:", $(this).val());
        toggleJenisTA();
    });


    function setMinDate() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        $('#tanggalTenggat').attr('min', minDate);
    }

    setMinDate();

    function updateRowStriping() {
        $("#aspekPenilaianTable tr, #aspekFeedbackTable tr").each(function (index) {
            if (index % 2 === 0) {
                $(this).css("background-color", "#ffffff");
            } else {
                $(this).css("background-color", "#f8f9fa"); 
            }
        });
    }

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
            validateTotalBobot();
        }
        updateRowStriping();
    });

    // Event listener untuk menghapus baris
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        if ($("#jenisForm").val() === "Penilaian") {
            validateTotalBobot();
        }
        updateRowStriping();
    });

    updateRowStriping();

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
    if ($("#jenisForm").val() === "Penilaian") {
        validateTotalBobot();
    }

    $('#aspekFormulirForm').on('submit', function(e) {
        if ($("#jenisForm").val() === "Penilaian") {
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
                
                return false;
            }
        }
        return true;
    });

    $('.btn-primary[type="submit"]').on('click', function() {
        console.log('Submit button clicked');
        $('#aspekFormulirForm').submit();
    });
});