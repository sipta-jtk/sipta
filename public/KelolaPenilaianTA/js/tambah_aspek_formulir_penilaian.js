$(document).ready(function () {
    $('#addRow').on('click', function () {
        var newRow = `<tr>
            <td><input type="text" class="form-control" name="kriteria[]" required></td>
            <td><input type="number" class="form-control" name="bobot[]" required></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
        </tr>`;
        $('#aspekPenilaianTable').append(newRow);
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
});