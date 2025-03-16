<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function SweetAlert(icon = 'info', title = '', text = '', confirmButtonText = 'OK', cancelButtonText = 'Cancel',
        confirmButtonColor = '#3085d6', cancelButtonColor = '#d33',
        showCancelButton = false, showConfirmButton = true, callback = () => {}) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: cancelButtonColor,
            showCancelButton: showCancelButton,
            showConfirmButton: showConfirmButton,
        }).then((result) => {
            callback(result.isConfirmed);
        });
    }

    function toast(icon = 'info', title = '', text = '', timer = 5000) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: timer,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: icon,
            title: title,
            text: text
        });
    }
</script>
