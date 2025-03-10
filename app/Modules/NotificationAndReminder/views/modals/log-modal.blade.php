<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h3 class="modal-title" id="notificationModalLabel">Notification</h3>
                <a href="#" id="openPreferences" class="close" aria-label="Close">
                    <i class="fas fa-cog"></i> <!-- Ikon pengaturan -->
                </a>
            </div>

            <!-- Notifikasi 1 -->
            <div class="notification-item d-flex justify-content-between align-items-center mb-3">
                <i class="fas fa-exclamation-circle"></i> <!-- Ikon Pemberitahuan -->
                <div>
                    <p><strong>Deadline Tugas Akhir Anda Mendekat!</strong></p>
                    <p>Harap periksa tenggat tugas akhir Anda dan pastikan semua persyaratan sudah lengkap.</p>
                </div>
                <button type="button" class="close close-notification" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Notifikasi 2 -->
            <div class="notification-item d-flex justify-content-between align-items-center mb-3">
                <i class="fas fa-exclamation-circle"></i> <!-- Ikon Pemberitahuan -->
                <div>
                    <p><strong>Pengingat: 5 Hari Menuju Tenggat Tugas Akhir</strong></p>
                    <p>Pastikan Anda menyelesaikan revisi dan persiapan akhir sebelum tenggat tugas akhir.</p>
                </div>
                <button type="button" class="close close-notification" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Notifikasi 3 -->
            <div class="notification-item d-flex justify-content-between align-items-center mb-3">
                <i class="fas fa-exclamation-circle"></i> <!-- Ikon Pemberitahuan -->
                <div>
                    <p><strong>10 Hari Menuju Pengumpulan Tugas Akhir</strong></p>
                    <p>Harap tinjau kembali semua instruksi dan pastikan tidak ada yang terlewat sebelum pengumpulan tugas akhir.</p>
                </div>
                <button type="button" class="close close-notification" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Tombol untuk melihat semua notifikasi -->
            <a href="/seeallnotif" class="btn btn-secondary w-100">Lihat semua notifikasi</a>
        </div>
    </div>
</div>

    
<!-- Styling CSS untuk Modal dan Notifikasi -->
<style>
    .modal-content {
        border-radius: 10px;
        padding: 7px;
        max-width: 400px;
        width: 100%;
        margin: auto;
        text-align: left;
        background-color: #f8f9fa;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
        position: relative;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .modal-header a.close {
        font-size: 24px;
        color: #000;
        text-decoration: none;
    }

    .modal-header a.close:hover {
        color: #f44336; /* Warna ketika hover */
    }

    .notification-item {
        background-color: #e0e0e0;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 8px;
        flex-wrap: wrap;
        position: relative;
        text-align: left;
        width: 100%;
    }

    /* Memperbaiki alignment teks dalam notifikasi */
    .notification-item div {
        text-align: left;
        margin-left: 10px;
        flex: 1;
    }

    .notification-item i {
        font-size: 30px;
        color: #ff9800; 
        margin-right: 10px;
    }

    .notification-item p {
        margin: 0;
        padding: 0;
        line-height: 1.8; 
    }

    .notification-item p strong {
        font-weight: bold;
        margin-bottom: 5px; 
    }

    /* Tombol close untuk setiap notifikasi (close-notification) */
    .notification-item button.close-notification {
        background: none;
        border: none;
        font-size: 20px;
        color: #888;
        position: absolute; /* Menempatkan close button di kanan atas notifikasi */
        top: 10px;
        right: 10px;
    }

    .notification-item button.close-notification:hover {
        color: #f44336;
    }

    .notification-item button.close {
        background: none;
        border: none;
        font-size: 20px;
        color:rgb(105, 96, 96);
    }

    .notification-item button.close:hover {
        color: #f44336;
    }

    .btn-secondary {
        /* background-color:rgb(135, 216, 121); */
        border: none;
        border-radius: 5px;
        padding: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .btn-secondary:hover {
        background-color: #ddd;
    }

    @media (max-width: 768px) {
        .modal-content {
            padding: 10px; /* Mengurangi padding pada layar kecil */
            max-height: 60vh;
        }

        .notification-item {
            padding: 10px; /* Mengurangi padding pada notifikasi */
        }
    }
</style>


<script>
    // Menghapus notifikasi ketika tombol 'x' diklik
    document.querySelectorAll('.close-notification').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.notification-item').remove();
        });
    });

    // Arahkan ke halaman lain ketika "Lihat semua notifikasi" diklik
    document.querySelector('.btn-secondary').addEventListener('click', function() {
        window.location.href = '/seeallnotif'; // Ubah dengan URL halaman notifikasi jika sudah ada
    });
</script>

<!-- Menyertakan jQuery dan Bootstrap JS untuk modal interactivity -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
