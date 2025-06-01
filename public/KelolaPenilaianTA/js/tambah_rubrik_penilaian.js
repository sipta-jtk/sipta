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

    $("#kode_fta").change(function () {
        validateKodeFTA();
        const selectedKodeFTA = $(this).val();
        const selectedFormPenilaian = formPenilaianList.find(
            (form) => form.kode_fta == selectedKodeFTA
        );
        
        $("#nama_fta").val(
            selectedFormPenilaian ? selectedFormPenilaian.nama_fta : ""
        );

        $(".id_kriteria, .kriteria").html(
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

        const trCount = $("#rubrikPenilaianTable tr").length;

        let nilaiColumns = '';
        if (typeof rentangNilai !== 'undefined') {
            rentangNilai.forEach(function (nilai) {
                nilaiColumns += `<td><textarea class="form-control textarea-rubrik" name="nilai_${nilai.id_nilai}[]" rows="6" required></textarea></td>`;
            });
        } else {
            $('#rubrikPenilaianTable').closest('table').find('thead th').each(function(index) {
                if (index > 2 && index < $(this).closest('tr').find('th').length - 1) {
                    const headerText = $(this).text();
                    const match = headerText.match(/\(([A-Za-z0-9]+)\)$/);
                    if (match && match[1]) {
                        const id_nilai = match[1];
                        nilaiColumns += `<td><textarea class="form-control textarea-rubrik" name="nilai_${id_nilai}[]" rows="6" required></textarea></td>`;
                    }
                }
            });
        }

        let kriteriaOptions = '';
        if (typeof kriteriaList !== 'undefined') {
            kriteriaOptions = kriteriaList.map(kriteria => 
                `<option value="${kriteria.id_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
            ).join('');
        } else {
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
            <td><textarea class="form-control textarea-rubrik" name="detail[]" rows="6" required></textarea></td>
            ${nilaiColumns}
            <td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;

        $("#rubrikPenilaianTable").append(newRow);
        updateRowStriping();
        calculateFrozenColumnWidth();
    });

    // Handle kriteria selection change (works for both .kriteria and .id_kriteria)
    $(document).on("change", ".id_kriteria, .kriteria", function () {
        var selectedOption = $(this).find(":selected");
        var bobot = selectedOption.data("bobot");
        $(this)
            .closest("tr")
            .find(".bobot")
            .text(bobot ? bobot + "%" : "-");
    });

    // Remove row when remove button is clicked
    $(document).on("click", ".remove-row", function () {
        if ($("#rubrikPenilaianTable tr").length > 1) {
            $(this).closest("tr").remove();
            updateRowStriping();
            calculateFrozenColumnWidth();
        } else {
            Swal.fire({
                icon: "error",
                title: "Tidak Bisa Hapus",
                text: "Tidak dapat menghapus baris terakhir.",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    });

    updateRowStriping();

    const initialKodeFTA = $("#kode_fta").val();
    if (initialKodeFTA) {
        loadKriteria(initialKodeFTA);
    }

    setTimeout(function() {
        calculateFrozenColumnWidth();
        updateRowStriping();
    }, 100);

    $(window).on('resize', function() {
        calculateFrozenColumnWidth();
    });
});