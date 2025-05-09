$(document).ready(function () {
    var table = $("#nilaiAkhirTable").DataTable({
        columnDefs: [
            {
                targets: 0, // Kolom pertama (No)
                searchable: false,
                orderable: false,
            },
        ],
        order: [[1, "asc"]],
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<",
            },
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-5'p>>",
        initComplete: function() {
            $("#infoControls").html($(".dataTables_info"));
            $("#paginationControls").html($(".dataTables_paginate"));
            $("#dataTableControls").html($(".dataTables_length"));
            $("#searchBox").html($(".dataTables_filter"));
        }
    });

    // Tombol Edit
    $('#edit-button').click(function () {
        $('.bobot-input, .sumber-nilai').prop('disabled', false); // Aktifkan input
        $('#submit-button').prop('disabled', false); // Aktifkan tombol Simpan
        $(this).prop('disabled', true); // Nonaktifkan tombol Edit
    });

    table
        .on("order.dt search.dt draw.dt", function () {
        table
            .column(0, { search: "applied", order: "applied" })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    })
    .draw();

    $('.btn-edit').click(function () {
        $('.bobot-input, .sumber-nilai').prop('disabled', false);
    });
    
    function validateBobot() {
        let totalBobot = 0;
        let allValid = true;

        $('.bobot-input').each(function () {
            let val = $(this).val().trim();
            if (val === '' || isNaN(val) || val < 0 || val > 100) {
                allValid = false;
            }
            totalBobot += parseFloat(val) || 0;
        });

        if (!allValid) return 'Bobot harus angka antara 0 - 100.';
        if (totalBobot !== 100) return 'Total bobot harus 100%. Saat ini: ' + totalBobot + '%';
        return null;
    }

    function checkDuplicateSumberNilai() {
        let sumberValues = {};
        let duplicates = [];

        $('.sumber-nilai').each(function(index) {
            let komponen = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            let val = $(this).val();
            
            if (sumberValues[val]) {
                duplicates.push({
                    komponen: komponen,
                    sumber: $('option[value="' + val + '"]', this).text().trim()
                });
            } else {
                sumberValues[val] = true;
            }
        });

        return duplicates;
    }

    function validateForm() {
        let errors = [];
        let warnings = [];
        let errorBobot = validateBobot();
        let duplicates = checkDuplicateSumberNilai();

        if (errorBobot) errors.push(errorBobot);
        
        // Handle duplicates as warnings instead of errors
        if (duplicates.length > 0) {
            let warningText = 'Ditemukan sumber nilai yang sama:';
            duplicates.forEach(function(item) {
                warningText += ` Komponen "${item.komponen}" menggunakan sumber "${item.sumber}" yang sudah digunakan.`;
            });
            warnings.push(warningText);
        }

        // Display errors if any
        if (errors.length > 0) {
            let errorListHtml = errors.map(error => `<li>${error}</li>`).join('');
            $('#error-list').html(errorListHtml);
            $('#error-messages').removeClass('d-none');
            $('#submit-button').prop('disabled', true);
        } else {
            $('#error-messages').addClass('d-none');
            $('#submit-button').prop('disabled', false);
        }
        
        // Display warnings if any
        if (warnings.length > 0) {
            let warningListHtml = warnings.map(warning => `<li>${warning}</li>`).join('');
            $('#warning-list').html(warningListHtml);
            $('#warning-message').removeClass('d-none');
        } else {
            $('#warning-message').addClass('d-none');
        }
        
        return errors.length === 0; // Form is valid if there are no errors (warnings are okay)
    }

    // Validasi saat form disubmit
    $('#nilaiAkhirForm').submit(function (e) {
        if (!validateForm()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan!',
                html: $('#error-list').html(),
            });
        } else if ($('#warning-message').is(':visible')) {
            // If there are warnings but no errors, confirm before submitting
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                html: $('#warning-list').html(),
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Lanjutkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#nilaiAkhirForm').off('submit').submit();
                }
            });
        }
    });

    // Live validation
    $('.bobot-input, .sumber-nilai').on('input change', function () {
        validateForm();
    });

    // Tampilkan error dari backend jika ada
    if ($('#backend-error').length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan!',
            text: $('#backend-error').text(),
        });
    }
});