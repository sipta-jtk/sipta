$(document).ready(function () {
    var table = $('#formulirTable').DataTable({
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

        $("#formulirTable tbody tr").each(function () {
            let prodi = $(this).find("td:eq(3)").text().trim();
            let namaForm = $(this).find("td:eq(2)").text().trim();
            let jenisForm = $(this).find("td:eq(4)").text().trim();
            if (prodi) prodiSet.add(prodi);
            if (namaForm) namaFormSet.add(namaForm);
            if (jenisForm) jenisFormSet.add(jenisForm);
        });

        let prodiDropdown = $("#filterProdi");
        prodiDropdown.empty().append('<option value="">Semua Program Studi</option>');
        prodiSet.forEach((prodi) => {
            prodiDropdown.append(<option value="${prodi}">${prodi}</option>);
        });

        let jenisFormDropdown = $("#filterJenisForm");
        jenisFormDropdown.empty().append('<option value="">Semua Jenis Formulir</option>');
        jenisFormSet.forEach((jenisForm) => {
            jenisFormDropdown.append(<option value="${jenisForm}">${jenisForm}</option>);
        });
    }
    
    updateFilterOptions();

    $("#applyFilter").click(function () {
        const selectedProdi = $("#filterProdi").val();
        const selectedJenisForm = $("#filterJenisForm").val();

        table.columns(3).search(selectedProdi).draw(); 
        table.columns(4).search(selectedJenisForm).draw();
    });
});