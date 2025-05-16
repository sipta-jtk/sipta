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

    const namaFormulirOptions = [
        "Seminar I",
        "Seminar II", 
        "Seminar III", 
        "Sidang Akhir", 
        "Dosen Pembimbing"
    ];
    
    const prodiOptions = [
        "D3-Teknik Informatika",
        "D4-Teknik Informatika"
    ];
    
    const jenisFormulirOptions = [
        "penilaian",
        "feedback"
    ];
    
    function setFilterOptions() {
        let namaFormDropdown = $("#filterNamaForm");
        namaFormDropdown.empty().append('<option value="">Semua Nama Formulir</option>');
        namaFormulirOptions.forEach((namaForm) => {
            namaFormDropdown.append(`<option value="${namaForm}">${namaForm}</option>`);
        });
        
        let prodiDropdown = $("#filterProdi");
        prodiDropdown.empty().append('<option value="">Semua Program Studi</option>');
        prodiOptions.forEach((prodi) => {
            prodiDropdown.append(`<option value="${prodi}">${prodi}</option>`);
        });
        
        let jenisFormDropdown = $("#filterJenisForm");
        jenisFormDropdown.empty().append('<option value="">Semua Jenis Formulir</option>');
        jenisFormulirOptions.forEach((jenisForm) => {
            jenisFormDropdown.append(`<option value="${jenisForm}">${jenisForm}</option>`);
        });
    }
    
    setFilterOptions();

    $('#applyFilter').click(function() {
        let prodiFilter = $('#filterProdi').val();
        let namaFormFilter = $('#filterNamaForm').val();
        let jenisFormFilter = $('#filterJenisForm').val();
        
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                let prodi = data[3].trim();
                let namaForm = data[2].trim();
                let jenisForm = data[4].trim();
                
                let matchProdi = prodiFilter === "" || prodi === prodiFilter;
                let matchNamaForm = namaFormFilter === "" || namaForm === namaFormFilter;
                let matchJenisForm = jenisFormFilter === "" || jenisForm === jenisFormFilter;
                
                return matchProdi && matchNamaForm && matchJenisForm;
            }
        );
        
        table.draw();
        $.fn.dataTable.ext.search.pop();
    });
});