$(document).ready(function () {
    $("#kode_fta").change(function () {
        validateKodeFTA();
        const selectedKodeFTA = $(this).val();
        const selectedFormPenilaian = formPenilaianList.find(
            (form) => form.kode_fta == selectedKodeFTA
        );
        $("#nama_fta").val(
            selectedFormPenilaian ? selectedFormPenilaian.nama_fta : ""
        );

        $(".kriteria").html(
            '<option value="" disabled selected>Pilih Kriteria</option>'
        );

        if (selectedKodeFTA) {
            loadKriteria(selectedKodeFTA);
        }
    });

    function validateKodeFTA() {
        let kodeFTA = $('#kodeFTA').val();
        let jenisForm = $('#jenisForm').val();
        
        if (kodeFTA && jenisForm) {
            $.ajax({
                url: '/kelola-penilaian-ta/formulir-penilaian/check-kode-fta',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    kodeFTA: kodeFTA,
                    jenisForm: jenisForm
                },
                success: function(response) {
                    if (!response.isUnique) {
                        $("#notification")
                            .removeClass("d-none alert-info alert-success")
                            .addClass("alert-danger")
                            .find("#notificationMessage")
                            .text("Kode FTA sudah digunakan untuk jenis formulir yang sama!");
                            
                        // Disable submit button
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        $("#notification").addClass("d-none");
                        // Enable submit button
                        $('button[type="submit"]').prop('disabled', false);
                    }
                },
                error: function() {
                    console.error('Error checking Kode FTA uniqueness');
                }
            });
        }
    }

    // Load kriteria based on selected FTA code
    function loadKriteria(kodeFTA) {
        $.ajax({
            url: `/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
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

                    $(".kriteria").each(function () {
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
    });

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
                // Using textarea instead of input
                nilaiColumns += `<td><textarea class="form-control textarea-rubrik" name="nilai_${nilai.id_nilai}[]" rows="6" required></textarea></td>`;
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
                        // Using textarea instead of input
                        nilaiColumns += `<td><textarea class="form-control textarea-rubrik" name="nilai_${id_nilai}[]" rows="6" required></textarea></td>`;
                    }
                }
            });
        }

        var newRow = `
        <tr>
            <td>
                <select class="form-control kriteria" name="nama_kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                </select>
            </td>
            <td><p class="form-control-plaintext bobot">-</p></td>
            <td><textarea class="form-control textarea-rubrik" name="detail[]" rows="6" required></textarea></td>
            ${nilaiColumns}
            <td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;

        $("#rubrikPenilaianTable").append(newRow);

        if (selectedKodeFTA) {
            $.ajax({
                url: `/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
                method: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.length > 0) {
                        const kriteriaOptions = data
                            .map(
                                (kriteria) =>
                                    `<option value="${kriteria.id}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
                            )
                            .join("");

                        $("#rubrikPenilaianTable tr:last .kriteria").html(
                            `<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`
                        );
                    }
                },
            });
        }
    });

    $(document).on("change", ".kriteria", function () {
        var bobot = $(this).find(":selected").data("bobot");
        $(this).closest("tr").find(".bobot").text(bobot);
    });

    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    $(document).on("change", ".id_kriteria", function () {
        var selectedOption = $(this).find(":selected"); // Ambil opsi yang dipilih
        var bobot = selectedOption.data("bobot"); // Ambil nilai dari atribut data-bobot
        $(this)
            .closest("tr")
            .find(".bobot")
            .text(bobot ? bobot + "%" : "-"); // Tampilkan bobot di kolom
        console.log("Data Bobot:", bobot); // ✅ Console log di sini
    });
});
