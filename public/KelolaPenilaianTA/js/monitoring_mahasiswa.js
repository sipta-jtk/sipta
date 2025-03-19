// document.addEventListener("DOMContentLoaded", function () {
//     // Seleksi semua tombol yang memiliki class 'disabled'
//     var disabledButtons = document.querySelectorAll("button.disabled");

//     disabledButtons.forEach(function (button, index) {
//         // Pastikan parent memiliki posisi relatif agar tooltip bisa muncul dengan benar
//         button.parentNode.style.position = "relative";

//         // Buat tooltip secara dinamis
//         var tooltip = document.createElement("div");
//         tooltip.className = "tooltip-box position-absolute bg-white border p-2 shadow rounded text-left d-none";
//         tooltip.style.left = "50%";
//         tooltip.style.transform = "translateX(-50%)";
//         tooltip.style.whiteSpace = "nowrap";
//         tooltip.style.zIndex = "1000"; // Pastikan tooltip berada di atas elemen lain
//         tooltip.innerHTML = "<strong>Feedback belum tersedia!</strong>";

//         // Berikan ID unik pada tooltip agar dapat digunakan di event listener
//         var tooltipId = "tooltip-" + index;
//         tooltip.setAttribute("id", tooltipId);

//         // Tambahkan tooltip ke dalam parent tombol
//         button.parentNode.appendChild(tooltip);

//         // Event hover untuk menampilkan tooltip
//         button.addEventListener("mouseover", function () {
//             showTooltip(tooltipId, button);
//         });

//         // Event mouseout untuk menyembunyikan tooltip
//         button.addEventListener("mouseout", function () {
//             hideTooltip(tooltipId);
//         });
//     });
// });

// // Fungsi untuk menampilkan tooltip dengan logika posisi yang sudah terbukti berhasil
// function showTooltip(id, button) {
//     var tooltip = document.getElementById(id);
//     tooltip.classList.remove("d-none");

//     // Hitung posisi tombol
//     var rect = button.getBoundingClientRect();
//     var tooltipHeight = tooltip.offsetHeight;
//     var windowHeight = window.innerHeight;

//     if (rect.bottom + tooltipHeight > windowHeight) {
//         // Jika tooltip akan keluar dari layar bawah, tampilkan di atas tombol
//         tooltip.style.top = "auto";
//         tooltip.style.bottom = "100%";
//     } else {
//         // Jika cukup ruang, tampilkan di bawah tombol
//         tooltip.style.top = "100%";
//         tooltip.style.bottom = "auto";
//     }
// }

// // Fungsi untuk menyembunyikan tooltip
// function hideTooltip(id) {
//     var tooltip = document.getElementById(id);
//     tooltip.classList.add("d-none");
// }

document.addEventListener("DOMContentLoaded", function () {
    var tooltipWrappers = document.querySelectorAll(".tooltip-wrapper");

    tooltipWrappers.forEach(function (wrapper) {
        var button = wrapper.querySelector("button");
        var tooltip = wrapper.querySelector(".tooltip-box");
        var url = button.dataset.url; // Ambil URL dari data attribute

        // Pastikan parent memiliki posisi relatif
        wrapper.style.position = "relative";

        // Atur tooltip agar tetap berada di atas tombol
        tooltip.style.left = "50%";
        tooltip.style.transform = "translateX(-50%)";
        tooltip.style.whiteSpace = "nowrap";
        tooltip.style.zIndex = "1000";

        // Event hover untuk tooltip (hanya muncul jika tombol 'disabled')
        wrapper.addEventListener("mouseover", function () {
            if (button.classList.contains("disabled")) {
                tooltip.classList.remove("d-none");
                positionTooltip(tooltip, button);
            }
        });

        wrapper.addEventListener("mouseout", function () {
            tooltip.classList.add("d-none");
        });

        // Klik tombol: jika tidak 'disabled', redirect ke route
        button.addEventListener("click", function () {
            if (!button.classList.contains("disabled")) {
                window.location.href = url;
            }
        });
    });
});

// Fungsi untuk menyesuaikan posisi tooltip
function positionTooltip(tooltip, button) {
    var rect = button.getBoundingClientRect();
    var tooltipHeight = tooltip.offsetHeight;
    var windowHeight = window.innerHeight;

    if (rect.bottom + tooltipHeight > windowHeight) {
        tooltip.style.top = "auto";
        tooltip.style.bottom = "100%";
    } else {
        tooltip.style.top = "100%";
        tooltip.style.bottom = "auto";
    }
}
