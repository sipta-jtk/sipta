$(document).ready(function () {
    function formatJenisFormulir(text) {
        if (!text) return text;
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

   $("#formulirTable tbody tr").each(function () {
        let jenisFormCell = $(this).find("td:eq(4)");
        let jenisFormText = jenisFormCell.text().trim();
        if (jenisFormText) {
            jenisFormCell.text(formatJenisFormulir(jenisFormText));
        }

        let jenisTACell = $(this).find("td:eq(5)");
        let jenisTAText = jenisTACell.text().trim();
        if (jenisTAText && jenisTAText !== '-') {
            jenisTACell.text(formatJenisFormulir(jenisTAText));
        }
    });

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var namaFormulir = data[2] || '';
            if (namaFormulir.trim() === 'Dosen Pembimbing') {
                return false;
            }
            return true;
        }
    );

    var table = $('#formulirTable').DataTable({
        columnDefs: [
            {
                targets: 0,         // Kolom pertama (No)
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
            info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<",
            },
        },
        initComplete: function () {
            $("#infoControls").html($(".dataTables_info"));
            $("#paginationControls").html($(".dataTables_paginate"));
            $("#dataTableControls").html($(".dataTables_length"));
            $("#searchBox").html($(".dataTables_filter"));
        },
    });

    $("#toggleFilter").click(function () {
        $("#filterSection").slideToggle();
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

    function updateFilterOptions() {
        let prodiSet = new Set();
        let jenisFormSet = new Set();
        let jenisTASet = new Set();

        table.rows({ search: 'applied' }).data().each(function(rowData) {
            let prodi = rowData[3] ? rowData[3].trim() : '';        // Program Studi
            let jenisForm = rowData[4] ? rowData[4].trim() : '';    // Jenis Formulir
            let jenisTA = rowData[5] ? rowData[5].trim() : '';      // Jenis TA
            if (prodi) prodiSet.add(prodi);
            if (jenisForm) jenisFormSet.add(jenisForm);
            if (jenisTA && jenisTA !== "-") jenisTASet.add(jenisTA);
        });

        let prodiDropdown = $("#filterProdi");
        prodiDropdown.empty().append('<option value="">Semua Program Studi</option>');
        prodiSet.forEach((prodi) => {
            prodiDropdown.append(`<option value="${prodi}">${prodi}</option>`);
        });

        let jenisFormDropdown = $("#filterJenisForm");
        jenisFormDropdown.empty().append('<option value="">Semua Jenis Formulir</option>');
        jenisFormSet.forEach((jenisForm) => {
            jenisFormDropdown.append(`<option value="${jenisForm}">${jenisForm}</option>`);
        });

        let jenisTADropdown = $("#filterJenisTA");
        jenisTADropdown.empty().append('<option value="">Semua Jenis TA</option>'); 
        jenisTASet.forEach((jenisTA) => {
            jenisTADropdown.append(`<option value="${jenisTA}">${jenisTA}</option>`);
        });
    }

    updateFilterOptions();

    $("#applyFilter").click(function () {
        const selectedProdi = $("#filterProdi").val();
        const selectedJenisForm = $("#filterJenisForm").val();
        const selectedJenisTA = $("#filterJenisTA").val();

        table.columns(3).search(selectedProdi).draw();
        table.columns(4).search(selectedJenisForm).draw();
        table.columns(5).search(selectedJenisTA).draw();
    });
});