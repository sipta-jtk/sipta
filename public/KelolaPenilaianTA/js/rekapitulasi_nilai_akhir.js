$(document).ready(function () {

    var table = $('#nilaiAkhirTable').DataTable({
        columnDefs: [
            {
                targets: 0, // Kolom pertama (No)
                searchable: false,
                orderable: false,
            }
        ],
        order: [[1, 'asc']],
        "paging": true,
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
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
        initComplete: function() {
            $("#infoControls").html($(".dataTables_info"));
            $("#paginationControls").html($(".dataTables_paginate"));
        }
    });

    $('#toggleFilter').click(function () {
        $('#filterSection').slideToggle(); // Efek animasi buka/tutup
    });

    table.on('order.dt search.dt draw.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
    }).draw();
    // Mengambil data prodi dan kelas unik dari tabel
    function updateFilterOptions() {
        let prodiSet = new Set();
        let kelasSet = new Set();

        $('#nilaiAkhirTable tbody tr').each(function () {
            let prodi = $(this).find("td:eq(3)").text().trim(); // Ambil nilai Prodi di kolom ke-3
            let kelas = $(this).find("td:eq(4)").text().trim(); // Ambil nilai Kelas di kolom ke-4
            if (prodi) prodiSet.add(prodi);
            if (kelas) kelasSet.add(kelas);
        });

        // Update dropdown filter prodi
        let prodiDropdown = $('#filterProdi');
        prodiDropdown.empty().append('<option value="">Semua Prodi</option>');
        prodiSet.forEach(prodi => {
            prodiDropdown.append(`<option value="${prodi}">${prodi}</option>`);
        });

        // Update dropdown filter kelas
        let kelasDropdown = $('#filterKelas');
        kelasDropdown.empty().append('<option value="">Semua Kelas</option>');
        kelasSet.forEach(kelas => {
            kelasDropdown.append(`<option value="${kelas}">${kelas}</option>`);
        });
    }

    // Jalankan fungsi update filter setelah tabel dimuat
    updateFilterOptions();

    $("#applyFilter").click(function () {
        const selectedProdi = $("#filterProdi").val();
        const selectedKelas = $("#filterKelas").val();

        table.columns(3).search(selectedProdi).draw();
        table.columns(4).search(selectedKelas).draw();
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
    let exportUrl = this.getAttribute("data-url"); // Ambil URL dari tombol

    let url = exportUrl + "?prodi=" + encodeURIComponent(filterProdi) + "&kelas=" + encodeURIComponent(filterKelas);
    window.location.href = url;
});