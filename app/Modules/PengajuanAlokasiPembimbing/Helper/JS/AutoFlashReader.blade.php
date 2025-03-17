<<<<<<< HEAD
@include('pengajuanalokasipembimbing.Helper.JS.SweetAlert')
=======
@include('PengajuanAlokasiPembimbing.Helper.JS.SweetAlert')
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

<script>
    $(document).ready(function() {
        @if (session()->has('success'))
            toast('success', 'Berhasil', '{{ session('success') }}');
        @elseif (session()->has('error'))
            toast('error', 'Gagal', '{{ session('error') }}');
        @endif
    });
</script>
