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
        tooltip.style.visibility = "hidden"; // Gunakan visibility daripada display

        // Event hover untuk tooltip (hanya muncul jika tombol 'disabled')
        wrapper.addEventListener("mouseover", function () {
            if (button.classList.contains("disabled")) {
                tooltip.style.visibility = "visible"; // Tampilkan tooltip
                positionTooltip(tooltip, button);
            }
        });

        wrapper.addEventListener("mouseout", function () {
            tooltip.style.visibility = "hidden"; // Sembunyikan tooltip
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
