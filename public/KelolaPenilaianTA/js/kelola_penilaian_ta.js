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
