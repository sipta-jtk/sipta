$(document).ready(function () {
    // Handle change of kriteria dropdown
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
            if (index % 2 === 0) {
                $(this).css("background-color", "#ffffff"); // Warna putih untuk baris ganjil
            } else {
                $(this).css("background-color", "#f8f9fa"); // Warna abu untuk baris genap
            }
        });
    }

    // Add new row to table
    $("#addRow").on("click", function () {
        const selectedKodeFTA = $("#kode_fta").val();
        if (!selectedKodeFTA) {
            alert("Silakan pilih Kode FTA terlebih dahulu.");
            return;
        }

        // Get rentangNilai from the page
        let nilaiColumns = '';
        // Check if rentangNilai is defined globally
        if (typeof rentangNilai !== 'undefined') {
            rentangNilai.forEach(function (nilai) {
                nilaiColumns += `
                    <td>
                        <textarea class="form-control textarea-rubrik" name="nilai_${nilai.id_nilai}[]" rows="6" required></textarea>
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
                        const id_nilai = match[1];
                        nilaiColumns += `
                            <td>
                                <textarea class="form-control textarea-rubrik" name="nilai_${id_nilai}[]" rows="6" required></textarea>
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
                <select class="form-control id_kriteria" name="nama_kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                    ${kriteriaList.map(kriteria => `<option value="${kriteria.id_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`).join('')}
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
    });

    // Remove row when remove button is clicked
    $(document).on("click", ".remove-row", function () {
        // Make sure we keep at least one row
        if ($("#rubrikPenilaianTable tr").length > 1) {
            $(this).closest("tr").remove();
            updateRowStriping();
        } else {
            alert("Tidak dapat menghapus baris terakhir.");
        }
    });

    updateRowStriping();

    // Konfirmasi sebelum submit form
    $("form").on("submit", function (e) {
        // Validasi form sebelum submit
        let valid = true;
        $("input[required], select[required]").each(function () {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("Mohon isi semua field yang diperlukan.");
            return false;
        }

        // Konfirmasi update
        if (!confirm("Apakah Anda yakin ingin menyimpan perubahan rubrik penilaian ini?")) {
            e.preventDefault();
            return false;
        }
    });

    // Inisialisasi: periksa bobot pada semua baris yang sudah ada
    $(".id_kriteria").each(function () {
        var selectedOption = $(this).find(":selected");
        var bobot = selectedOption.data("bobot");
        if (!bobot) {
            // Jika data-bobot tidak ada, coba ambil dari teks yang sudah ada
            bobot = $(this).closest("tr").find(".bobot").text().replace("%", "");
        }
        $(this).closest("tr").find(".bobot").text(bobot ? bobot + "%" : "-");
    });
});