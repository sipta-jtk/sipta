$(document).ready(function () {
    $('#formulirTable').DataTable({
        "paging": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
    });
});