@include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')

<script>
    $(document).ready(function() {
        @if (session()->has('success'))
            toast('success', 'Berhasil', '{{ session('success') }}');
        @elseif (session()->has('error'))
            toast('error', 'Gagal', '{{ session('error') }}');
        @endif
    });
</script>
