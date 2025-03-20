$(document).ready(function () {
    let jenisForm = $('#jenisForm');

    function toggleTables() {
        let jenis = jenisForm.val();
        $('#tablePenilaian').toggle(jenis === 'Penilaian');
        $('#tableFeedback').toggle(jenis === 'Feedback');
    }

    // Inisialisasi tampilan saat halaman dimuat
    toggleTables();

    // Event listener untuk perubahan pilihan
    jenisForm.change(toggleTables);

    // Event listener untuk menambah baris
    $("#addRow").click(function() {
        if ($("#jenisForm").val() === "Feedback") {
            $("#aspekFeedbackTable").append(`
                <tr>
                    <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </td>
                </tr>
            `);
        } else {
            $("#aspekPenilaianTable").append(`
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
        }
    });

    // Event listener untuk menghapus baris
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    // Log untuk debugging saat form disubmit
    $('#aspekFormulirForm').on('submit', function(e) {
        console.log('Form submitted');
    });

    // Memastikan tombol bisa memicu submit
    $('.btn-primary').on('click', function() {
        console.log('Button clicked');
        $('#aspekFormulirForm').submit();
    });
});


// $(document).ready(function () {
//     $('#jenisForm').change(function() {
//         var jenisForm = $(this).val();
//         if (jenisForm === 'Penilaian') {
//             $('#tablePenilaian').show();
//             $('#tableFeedback').hide();
//         } else if (jenisForm === 'Feedback') {
//             $('#tablePenilaian').hide();
//             $('#tableFeedback').show();
//         }
//     });

//     $('#addRow').on('click', function () {
//         var jenisForm = $('#jenisForm').val();
//         var newRow = '';
//         if (jenisForm === 'Penilaian') {
//             newRow = `<tr>
//                 <td><input type="text" class="form-control" name="kriteria[]" required></td>
//                 <td><input type="number" class="form-control" name="bobot[]" required></td>
//                 <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
//             </tr>`;
//             $('#aspekPenilaianTable').append(newRow);
//         } else if (jenisForm === 'Feedback') {
//             newRow = `<tr>
//                 <td><input type="text" class="form-control" name="kriteria[]" required></td>
//                 <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
//             </tr>`;
//             $('#aspekFeedbackTable').append(newRow);
//         }
//     });

//     $(document).on('click', '.remove-row', function () {
//         $(this).closest('tr').remove();
//     });

//     $('#aspekFormulirForm').on('submit', function(e) {
//         console.log('Form submitted');
//         // Remove any code that might be preventing form submission
//     });
    
//     // Add a direct click handler to the button
//     $('.btn-primary').on('click', function() {
//         console.log('Button clicked');
//         $('#aspekFormulirForm').submit();
//     });
// });