$(document).ready(function () {
    // Add new row to Aspek Penilaian table
    $("#addRow").on("click", function () {
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
        $("#aspekPenilaianTable").append(newRow);
    });

    // Add new row to Aspek Feedback table
    $("#addFeedbackRow").on("click", function () {
        var newRow = `
        <tr>
            <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-feedback-row">
                    <i class="fa-solid fa-minus"></i>
                </button>
            </td>
        </tr>`;
        $("#aspekFeedbackTable").append(newRow);
    });

    // Remove row from Aspek Penilaian table
    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    // Remove row from Aspek Feedback table
    $(document).on("click", ".remove-feedback-row", function () {
        $(this).closest("tr").remove();
    });

    // Log for debugging when form is submitted
    $('#aspekForm').on('submit', function(e) {
        console.log('Form submitted');
    });

    // Ensure button triggers form submission
    $('.btn-primary').on('click', function() {
        console.log('Button clicked');
        $('#aspekForm').submit();
    });
});