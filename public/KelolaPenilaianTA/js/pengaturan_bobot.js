$(document).ready(function () {
    $('.btn-edit').click(function () {
        $('.bobot-input, .sumber-nilai').prop('disabled', false);
    });
    function validateBobot() {
        let totalBobot = 0;
        let allValid = true;

        $('.bobot-input').each(function () {
            let val = $(this).val().trim();
            if (val === '' || isNaN(val) || val < 0 || val > 100) {
                allValid = false;
            }
            totalBobot += parseFloat(val) || 0;
        });

        if (!allValid) return 'Bobot harus angka antara 0 - 100.';
        if (totalBobot !== 100) return 'Total bobot harus 100%. Saat ini: ' + totalBobot + '%';
        return null;
    }

    function validateSumberNilai() {
        let sumberSet = new Set();
        let isValid = true;

        $('.sumber-nilai').each(function () {
            let val = $(this).val();
            if (sumberSet.has(val)) {
                isValid = false;
            } else {
                sumberSet.add(val);
            }
        });

        if (!isValid) {
            return 'Setiap sumber nilai harus unik. Tidak boleh ada duplikat.';
        }
        return null;
    }

    function validateForm() {
        let errors = [];
        let errorBobot = validateBobot();
        let errorSumber = validateSumberNilai();

        if (errorBobot) errors.push(errorBobot);
        if (errorSumber) errors.push(errorSumber);

        if (errors.length > 0) {
            let errorListHtml = errors.map(error => `<li>${error}</li>`).join('');
            $('#error-list').html(errorListHtml);
            $('#error-messages').removeClass('d-none');
            $('#submit-button').prop('disabled', true);
            return false;
        } else {
            $('#error-messages').addClass('d-none');
            $('#submit-button').prop('disabled', false);
            return true;
        }
    }

    // Validasi saat form disubmit
    $('#nilaiAkhirForm').submit(function (e) {
        if (!validateForm()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan!',
                html: $('#error-list').html(),
            });
        }
    });

    // Live validation
    $('.bobot-input, .sumber-nilai').on('input change', function () {
        let isValid = validateForm();
        if (isValid) {
            $('#error-messages').addClass('d-none');
            $('#submit-button').prop('disabled', false);
        }
    });

    // Tampilkan error dari backend jika ada
    if ($('#backend-error').length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan!',
            text: $('#backend-error').text(),
        });
    }
});