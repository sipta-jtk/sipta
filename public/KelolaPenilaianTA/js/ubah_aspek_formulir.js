$(document).ready(function () {
    $('#jenisForm').change(function () {
        var jenisForm = $(this).val();
        if (jenisForm === 'Penilaian') {
            $('#tablePenilaian').show();
            $('#tableFeedback').hide();
        } else if (jenisForm === 'Feedback') {
            $('#tablePenilaian').hide();
            $('#tableFeedback').show();
        }
    });

    $('#addRow').on('click', function () {
        var jenisForm = $('#jenisForm').val();
        if (jenisForm === 'Penilaian') {
            var newRow = `
            <tr>
                <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>`;
            $('#aspekPenilaianTable').append(newRow);
        } else if (jenisForm === 'Feedback') {
            var newRow = `
            <tr>
                <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>`;
            $('#aspekFeedbackTable').append(newRow);
        }
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
});