$(document).ready(function () {
    // Initialize DataTable
    var table = $("#alokasiTable").DataTable({
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        columnDefs: [
            { searchable: false, targets: [6, 7, 8] }, // Adjusted target indices - double check your column count
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
        dom:
            "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        initComplete: function () {
            // Move info and pagination controls to custom div elements
            $("#infoControls").html($(".dataTables_info"));
            $("#paginationControls").html($(".dataTables_paginate"));
        },
    });

    // Event handler for detail buttons
    $(".buka-detail").on("click", function (event) {
        var idKategori = $(this).data("id");
        localStorage.setItem("id_kategori", idKategori);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.querySelector('input[type="file"]');
    if (!fileInput) return;

    const allowedExtensions = ["xls", "xlsx", "csv"];

    fileInput.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;

        const fileExtension = file.name.split(".").pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            alert("File harus berformat .xls, .xlsx, atau .csv!");
            this.value = ""; // Reset input file
        }

        const label = fileInput
            .closest(".input-group")
            ?.querySelector(".custom-file-label");
        if (label) {
            label.textContent = file.name;
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.querySelector('input[type="file"]');
    const label = fileInput
        .closest(".input-group")
        .querySelector(".custom-file-label");

    fileInput.addEventListener("change", function () {
        if (this.files.length > 0) {
            label.textContent = this.files[0].name;
        } else {
            label.textContent = "Choose a file...";
        }
    });
});
