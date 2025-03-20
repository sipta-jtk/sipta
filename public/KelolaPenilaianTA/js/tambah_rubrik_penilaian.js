$(document).ready(function () {
    const bobotMapping = {
        'Dokumen': 35,
        'Presentasi': 15,
        'Tanya Jawab': 35,
        'Prototipe': 15
    };

    // Pastikan variabel `formPenilaianList` dan `kriteriaList` sudah didefinisikan di Blade
    if (typeof formPenilaianList !== 'undefined' && typeof kriteriaList !== 'undefined') {
        $('#kode_fta').change(function () {
            const selectedKodeFTA = $(this).val();
            const selectedFormPenilaian = formPenilaianList.find(form => form.kode_fta == selectedKodeFTA);
            $('#nama_fta').val(selectedFormPenilaian ? selectedFormPenilaian.nama_fta : '');
        });
    } else {
        console.error("Data formPenilaianList atau kriteriaList tidak ditemukan.");
    }

    $('#addRow').on('click', function () {
        var newRow = `
        <tr>
            <td>
                <select class="form-control kriteria" name="kriteria[]" required>
                    <option value="" disabled selected>Pilih Kriteria</option>
                    ${kriteriaList.map(kriteria => `<option value="${kriteria.nama_kriteria}">${kriteria.nama_kriteria}</option>`).join('')}
                </select>
            </td>
            <td><p class="form-control-plaintext bobot">35</p></td>
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
    });

    $(document).on('change', '.kriteria', function () {
        var kriteria = $(this).val();
        var bobot = bobotMapping[kriteria] || 0;
        $(this).closest('tr').find('.bobot').text(bobot);
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
});