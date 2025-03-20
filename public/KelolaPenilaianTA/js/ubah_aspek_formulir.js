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

    // Validasi total bobot kriteria sebelum submit
    // $('#aspekForm').on('submit', function(e) {
    //     if (!validateTotalBobot()) {
    //         e.preventDefault();
    //         $('#alertBobot').show();
    //         setTimeout(function() {
    //             $('#alertBobot').hide();
    //         }, 3000);
    //     }
    // });

    // // Fungsi untuk validasi total bobot
    // function validateTotalBobot() {
    //     var totalBobot = 0;
    //     $('.bobot-kriteria').each(function() {
    //         var bobot = parseInt($(this).val()) || 0;
    //         totalBobot += bobot;
    //     });

    //     if (totalBobot !== 100) {
    //         $('#alertBobot').show();
    //         return false;
    //     } else {
    //         $('#alertBobot').hide();
    //         return true;
    //     }
    // }

    // Validasi total bobot kriteria sebelum submit
    // $('#aspekForm').on('submit', function(e) {
    //     if (!validateTotalBobot()) {
    //         e.preventDefault(); // Stop form submission
    //         $('#alertBobot').show().text('Total bobot harus 100 persen. Saat ini: ' + calculateTotalBobot() + '%');
    //     }
    // });

    // // Fungsi untuk validasi total bobot
    // function validateTotalBobot() {
    //     var totalBobot = calculateTotalBobot();
    //     if (totalBobot !== 100) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }

    // // Fungsi untuk menghitung total bobot
    // function calculateTotalBobot() {
    //     var totalBobot = 0;
    //     $('.bobot-kriteria').each(function() {
    //         totalBobot += parseInt($(this).val()) || 0;
    //     });
    //     return totalBobot;
    // }

    // // Event listener untuk perubahan bobot
    // $(document).on('input', '.bobot-kriteria', function() {
    //     var totalBobot = calculateTotalBobot();
    //     if (totalBobot !== 100) {
    //         $('#alertBobot').show().text('Total bobot saat ini: ' + totalBobot + '%. Harus mencapai 100%.');
    //     } else {
    //         $('#alertBobot').hide();
    //     }
    // });
});