$(document).ready(function () {
    var table = $("#alokasiTable").DataTable({
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        columnDefs: [
            { searchable: false, targets: [6, 7, 8, 9] },
            { orderable: false, targets: [3, 4, 5] },
        ],
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
        }
    });

    $("#dataTableControls").html($(".dataTables_length"));
    $("#searchBox").html($(".dataTables_filter"));
});

$(document).ready(function () {
    $(".buka-detail").on("click", function (event) {
        var idKategori = $(this).data("id");
        localStorage.setItem("id_kategori", idKategori);
    });
});

