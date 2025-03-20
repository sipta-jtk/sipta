$(document).ready(function () {
    $('#kode_fta').change(function () {
        const selectedKodeFTA = $(this).val();
        const selectedFormPenilaian = formPenilaianList.find(form => form.kode_fta == selectedKodeFTA);
        $('#nama_fta').val(selectedFormPenilaian ? selectedFormPenilaian.nama_fta : '');

        // Fetch kriteria based on selected kode FTA
        $.ajax({
            url: `/kelola-penilaian-ta/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
            method: 'GET',
            success: function (data) {
                const kriteriaOptions = data.map(kriteria => `<option value="${kriteria.nama_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`).join('');
                $('.kriteria').html(`<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`);
            }
        });
    });

    $('#addRow').on('click', function () {
        var newRow = `
        <tr>
            <td>
                <select class="form-control kriteria" name="kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                </select>
            </td>
            <td><p class="form-control-plaintext bobot"></p></td>
            <td><input type="text" class="form-control" name="detail[]" required></td>
            <td><input type="text" class="form-control" name="lebih80[]" required></td>
            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" required></td>
            <td><input type="text" class="form-control" name="tujuhPuluh[]" required></td>
            <td><input type="text" class="form-control" name="enamPuluhLima[]" required></td>
            <td><input type="text" class="form-control" name="enamPuluh[]" required></td>
            <td><input type="text" class="form-control" name="kurang60[]" required></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="fa-solid fa-minus"></i>
            </button></td>
        </tr>`;
        $('#rubrikPenilaianTable').append(newRow);

        // Re-fetch kriteria options for the new row
        const selectedKodeFTA = $('#kode_fta').val();
        if (selectedKodeFTA) {
            $.ajax({
                url: `/kelola-penilaian-ta/formulir-penilaian/get-kriteria/${selectedKodeFTA}`,
                method: 'GET',
                success: function (data) {
                    const kriteriaOptions = data.map(kriteria => `<option value="${kriteria.nama_kriteria}" data-bobot="${kriteria.bobot_kriteria}">${kriteria.nama_kriteria}</option>`).join('');
                    $('#rubrikPenilaianTable tr:last .kriteria').html(`<option value="" disabled selected>Pilih Kriteria</option>${kriteriaOptions}`);
                }
            });
        }
    });

    $(document).on('change', '.kriteria', function () {
        var bobot = $(this).find(':selected').data('bobot');
        $(this).closest('tr').find('.bobot').text(bobot);
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
});