$(document).ready(function () {
    // Handle change of FTA code dropdown
    $("#kode_fta").change(function () {
        const selectedKodeFTA = $(this).val();
        const selectedFormPenilaian = formPenilaianList.find(
            (form) => form.kode_fta == selectedKodeFTA
        );
        
        $("#nama_fta").val(
            selectedFormPenilaian ? selectedFormPenilaian.nama_fta : ""
        );

        // Reset all kriteria dropdowns
        $(".id_kriteria, .kriteria").html(
            '<option value="" disabled selected>Pilih Kriteria</option>'
        );

        if (selectedKodeFTA) {
            loadKriteria(selectedKodeFTA);
        }
    });

    // Load kriteria based on selected FTA code
    function loadKriteria(kodeFTA) {
        $.ajax({
            url: `/kelola-penilaian-ta/formulir-penilaian/get-kriteria/${kodeFTA}`,
            method: "GET",
            dataType: "json",
            success: function (data) {
                console.log("Received data:", data);

                if (data.length > 0) {
                    const kriteriaOptions = data
                        .map(
                            (kriteria) =>
                                `<option value="${kriteria.id}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
                        )
                        .join("");

                    // Update all kriteria dropdowns
                    $(".id_kriteria, .kriteria").each(function () {
                        $(this).html(
                            `<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`
                        );
                    });
                } else {
                    console.log("No criteria found for this FTA code");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching criteria:", error);
                console.log("Response:", xhr.responseText);
            },
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
                nilaiColumns += `<td><input type="text" class="form-control" name="nilai_${nilai.id_nilai}[]" required></td>`;
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
                        nilaiColumns += `<td><input type="text" class="form-control" name="nilai_${id_nilai}[]" required></td>`;
                    }
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
            <td><input type="text" class="form-control" name="detail[]" required></td>
            ${nilaiColumns}
            <td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;

        $("#rubrikPenilaianTable").append(newRow);
    });

    // Handle kriteria selection change (works for both .kriteria and .id_kriteria)
    $(document).on("change", ".id_kriteria, .kriteria", function () {
        var selectedOption = $(this).find(":selected");
        var bobot = selectedOption.data("bobot");
        $(this)
            .closest("tr")
            .find(".bobot")
            .text(bobot ? bobot + "%" : "-");
        console.log("Selected criteria with bobot:", bobot);
    });

    // Remove row when remove button is clicked
    $(document).on("click", ".remove-row", function () {
        // Make sure we keep at least one row
        if ($("#rubrikPenilaianTable tr").length > 1) {
            $(this).closest("tr").remove();
        } else {
            alert("Tidak dapat menghapus baris terakhir.");
        }
    });

    // If kode_fta already has a value on page load, load its kriteria
    const initialKodeFTA = $("#kode_fta").val();
    if (initialKodeFTA) {
        loadKriteria(initialKodeFTA);
    }
});