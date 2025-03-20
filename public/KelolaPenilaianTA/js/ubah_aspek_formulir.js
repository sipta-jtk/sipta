$(document).ready(function() {
    // Add row button functionality for penilaian
    $('#addRow').click(function() {
        $('#aspekPenilaianTable').append(`
            <tr>
                <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>
        `);
    });
    
    // Add row button functionality for feedback
    $('#addFeedbackRow').click(function() {
        $('#aspekFeedbackTable').append(`
            <tr>
                <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-feedback-row">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>
        `);
    });
    
    // Remove row button functionality for penilaian
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });
    
    // Remove row button functionality for feedback
    $(document).on('click', '.remove-feedback-row', function() {
        $(this).closest('tr').remove();
    });

});