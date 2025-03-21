$(document).ready(function () {
    $("#kode_fta").change(function () {
        const selectedKodeFTA = $(this).val();
        const selectedFormPenilaian = formPenilaianList.find(
            (form) => form.kode_fta == selectedKodeFTA
        );
        $("#nama_fta").val(
            selectedFormPenilaian ? selectedFormPenilaian.nama_fta : ""
        );

        $(".kriteria").html(
            '<option value="" disabled selected>Pilih Kriteria</option>'
        );

        $.ajax({
            url: `/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
            method: "GET",
            dataType: "json",
            success: function (data) {
                console.log("Received data:", data);

                if (data.length > 0) {
                    const kriteriaOptions = data
                        .map(
                            (kriteria) =>
                                `<option value="${kriteria.id}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
                        )
                        .join("");

                    $(".kriteria").each(function () {
                        $(this).html(
                            `<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`
                        );
                    });
                } else {
                    console.log("No criteria found for this FTA code");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching criteria:", error);
                console.log("Response:", xhr.responseText);
            },
        });
    });

    $("#addRow").on("click", function () {
        const selectedKodeFTA = $("#kode_fta").val();
        if (!selectedKodeFTA) {
            alert("Silakan pilih Kode FTA terlebih dahulu.");
            return;
        }

        var newRow = `
        <tr>
            <td>
                <select class="form-control kriteria" name="nama_kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                </select>
            </td>
            <td><p class="form-control-plaintext bobot"></p></td>
            <td><input type="text" class="form-control" name="detail[]" required></td>`;

        rentangNilai.forEach(function (nilai) {
            newRow += `<td><input type="text" class="form-control" name="nilai_${nilai.id_nilai}[]" required></td>`;
        });

        newRow += `<td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;

        $("#rubrikPenilaianTable").append(newRow);

        if (selectedKodeFTA) {
            $.ajax({
                url: `/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
                method: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.length > 0) {
                        const kriteriaOptions = data
                            .map(
                                (kriteria) =>
                                    `<option value="${kriteria.id}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`
                            )
                            .join("");

                        $("#rubrikPenilaianTable tr:last .kriteria").html(
                            `<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`
                        );
                    }
                },
            });
        }
    });

    $(document).on("change", ".kriteria", function () {
        var bobot = $(this).find(":selected").data("bobot");
        $(this).closest("tr").find(".bobot").text(bobot);
    });

    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    $(document).on("change", ".id_kriteria", function () {
        var selectedOption = $(this).find(":selected"); // Ambil opsi yang dipilih
        var bobot = selectedOption.data("bobot"); // Ambil nilai dari atribut data-bobot
        $(this)
            .closest("tr")
            .find(".bobot")
            .text(bobot ? bobot + "%" : "-"); // Tampilkan bobot di kolom
        console.log("Data Bobot:", bobot); // ✅ Console log di sini
    });
});
