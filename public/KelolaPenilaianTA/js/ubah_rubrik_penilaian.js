$(document).ready(function () {
    function capitalizeFirstLetter(text) {
        if (!text) return text;
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    let jenisTAField = $("#jenisTA");
    if (jenisTAField.length) {
        let jenisTAValue = jenisTAField.val().trim();
        if (jenisTAValue) {
            jenisTAField.val(capitalizeFirstLetter(jenisTAValue));
        }
    }
    
    $(document).on("change", ".id_kriteria", function () {
        var selectedOption = $(this).find(":selected");
        var bobot = selectedOption.data("bobot");
        $(this)
            .closest("tr")
            .find(".bobot")
            .text(bobot ? bobot + "%" : "-");
        console.log("Selected criteria with bobot:", bobot);
    });

    function updateRowStriping() {
        $("#rubrikPenilaianTable tr").each(function (index) {
            $(this).removeClass('even-row odd-row');
            if (index % 2 === 0) {
                $(this).addClass('odd-row');
                $(this).find('td:nth-child(1), td:nth-child(2)').css("background-color", "#ffffff");
            } else {
                $(this).addClass('even-row');
                $(this).find('td:nth-child(1), td:nth-child(2)').css("background-color", "#f8f9fa");
            }
        });
    }

    // Function to calculate frozen column width
    function calculateFrozenColumnWidth() {
        const firstColumnWidth = $('.table th:nth-child(1)').outerWidth() || 200;
        $('.table th:nth-child(2), .table td:nth-child(2)').css('left', firstColumnWidth + 'px');
    }

    // Add new row to table
    $("#addRow").on("click", function () {
        const selectedKodeFTA = $("#kode_fta").val();
        if (!selectedKodeFTA) {
            Swal.fire({
                icon: "error",
                title: "Kode FTA Belum Dipilih",
                text: "Silakan pilih Kode FTA terlebih dahulu.",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
            return;
        }

        // Hitung jumlah tr yang ada saat ini
        const trCount = $("#rubrikPenilaianTable tr").length;

        // Get rentangNilai from the page
        let nilaiColumns = '';
        // Check if rentangNilai is defined globally
        if (typeof rentangNilai !== 'undefined') {
            rentangNilai.forEach(function (nilai) {
                nilaiColumns += `
                    <td>
                        <textarea class="form-control textarea-rubrik" name="nilai_${trCount}[]" rows="6" required></textarea>
                    </td>`;
            });
        } else {
            // Fallback: get values from existing table headers
            $('#rubrikPenilaianTable').closest('table').find('thead th').each(function(index) {
                if (index > 2 && index < $(this).closest('tr').find('th').length - 1) {
                    // Extract id_nilai from header text using regex
                    const headerText = $(this).text();
                    const match = headerText.match(/\(([A-Za-z0-9]+)\)$/);
                    if (match && match[1]) {
                        // Gunakan trCount sebagai index
                        nilaiColumns += `
                            <td>
                                <textarea class="form-control textarea-rubrik" name="nilai_${trCount}[]" rows="6" required></textarea>
                            </td>`;
                    }
                }
            });
        }

        // Dapatkan semua kriteria yang tersedia
        let kriteriaOptions = '';
        // Periksa apakah kriteriaList didefinisikan secara global
        if (typeof kriteriaList !== 'undefined') {
            kriteriaOptions = kriteriaList.map(kriteria => 
                `<option value="${kriteria.id_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
            ).join('');
        } else {
            // Ambil opsi kriteria dari baris yang sudah ada
            $('#rubrikPenilaianTable tr:first .id_kriteria option').each(function() {
                if ($(this).val()) {
                    kriteriaOptions += `<option value="${$(this).val()}" data-bobot="${$(this).data('bobot')}">${$(this).text()}</option>`;
                }
            });
        }

        var newRow = `
        <tr>
            <td>
                <input type="hidden" name="status[]" value="1">
                <select class="form-control id_kriteria" name="nama_kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                    ${typeof kriteriaList !== 'undefined' ? 
                        kriteriaList.map(kriteria => `<option value="${kriteria.id_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`).join('') :
                        kriteriaOptions
                    }
                </select>
            </td>
            <td><p class="form-control-plaintext bobot">-</p></td>
            <td>
                <textarea class="form-control textarea-rubrik" name="detail[]" rows="6" required></textarea>
            </td>
            ${nilaiColumns}
            <td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;

        $("#rubrikPenilaianTable").append(newRow);
        updateRowStriping();
        calculateFrozenColumnWidth(); // Recalculate frozen column positions
    });

    $(document).on("click", ".remove-row", function () {
        var $row = $(this).closest("tr");
        var $statusInput = $row.find('input[name="status[]"]');
        var visibleRows = $("#rubrikPenilaianTable tr:visible").length;

        if (visibleRows <= 1) {
            Swal.fire({
                icon: "error",
                title: "Tidak Bisa Hapus",
                text: "Tidak dapat menghapus baris terakhir.",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
            return;
        }

        if ($statusInput.length) {
            $statusInput.val("0");
            $row.hide();
        } else {
            // Untuk baris baru yang belum ada status, tetap hapus dari DOM
            $row.remove();
        }
        updateRowStriping();
        calculateFrozenColumnWidth(); // Recalculate frozen column positions
    });

    updateRowStriping();

    $("#showKonfirmasiModal").on("click", function () {
        // Validasi Form
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
                title: "Oops...",
                text: "Mohon isi semua field yang diperlukan.",
            });
            return false;
        }

        Swal.fire({
            title: "Konfirmasi Simpan",
            text: "Apakah Anda yakin ingin menyimpan perubahan rubrik penilaian ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $("#rubrikPenilaianForm").submit();
            }
        });
    });

    $(".id_kriteria").each(function () {
        var selectedOption = $(this).find(":selected");
        var bobot = selectedOption.data("bobot");
        if (!bobot) {
            bobot = $(this).closest("tr").find(".bobot").text().replace("%", "");
        }
        $(this).closest("tr").find(".bobot").text(bobot ? bobot + "%" : "-");
    });

    // Initialize frozen columns on page load
    setTimeout(function() {
        calculateFrozenColumnWidth();
        updateRowStriping();
    }, 100);

    // Recalculate on window resize
    $(window).on('resize', function() {
        calculateFrozenColumnWidth();
    });
});