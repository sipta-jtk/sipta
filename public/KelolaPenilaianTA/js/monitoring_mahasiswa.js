document.addEventListener("DOMContentLoaded", function () {
    var tooltipWrappers = document.querySelectorAll(".tooltip-wrapper");

    tooltipWrappers.forEach(function (wrapper) {
        var link = wrapper.querySelector("a"); // Ubah dari button ke a
        var tooltip = wrapper.querySelector(".tooltip-box");
        var url = link.getAttribute("href"); // Ambil URL dari href

        // Pastikan parent memiliki posisi relatif
        wrapper.style.position = "relative";

        // Atur tooltip agar tetap berada di atas tombol
        tooltip.style.left = "50%";
        tooltip.style.transform = "translateX(-50%)";
        tooltip.style.whiteSpace = "nowrap";
        tooltip.style.zIndex = "1000";
        tooltip.style.visibility = "hidden"; // Gunakan visibility daripada display

        // Event hover untuk tooltip (hanya muncul jika link 'disabled-link')
        wrapper.addEventListener("mouseover", function () {
            if (link.classList.contains("disabled-link")) {
                tooltip.style.visibility = "visible"; // Tampilkan tooltip
                positionTooltip(tooltip, link);
            }
        });

        wrapper.addEventListener("mouseout", function () {
            tooltip.style.visibility = "hidden"; // Sembunyikan tooltip
        });

        // Klik link: jika tidak 'disabled-link', redirect ke route
        link.addEventListener("click", function (event) {
            if (link.classList.contains("disabled-link")) {
                event.preventDefault(); // Mencegah pengalihan halaman jika tombol dinonaktifkan
            }
        });
    });
});

// Fungsi untuk menyesuaikan posisi tooltip
function positionTooltip(tooltip, link) {
    var rect = link.getBoundingClientRect();
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
