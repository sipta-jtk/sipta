$(document).ready(function () {
    var table = $('#nilaiTable').DataTable({
        "paging": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
    });
    $('#filterProdi').on('change', function () {
        table.column(3).search(this.value).draw();
    });

    // Filter Kelas
    $('#filterKelas').on('change', function () {
        table.column(4).search(this.value).draw();
    });
    $('#dataTableControls').html($('.dataTables_length'));
    $('#searchBox').html($('.dataTables_filter'));
});

function showTooltip(id) {
    document.getElementById(id).classList.remove('d-none');
}

function hideTooltip(id) {
    document.getElementById(id).classList.add('d-none');
}

document.getElementById("exportExcel").addEventListener("click", function () {
    let filterProdi = document.getElementById("filterProdi").value;
    let filterKelas = document.getElementById("filterKelas").value;
    let exportUrl = this.getAttribute("data-url");

    let url = exportUrl + "?prodi=" + encodeURIComponent(filterProdi) + "&kelas=" + encodeURIComponent(filterKelas);
    window.location.href = url;
});