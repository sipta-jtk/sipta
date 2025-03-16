<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h3 class="modal-title" id="notificationModalLabel">Notification</h3>
                <a href="#" id="openPreferences" class="close" aria-label="Close">
                    <i class="fas fa-cog"></i> 
                </a>
            </div>
            <div id="notification-list"></div>

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

    .notification-item button.close-notification {
        background: none;
        border: none;
        font-size: 20px;
        color: #888;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .notification-item button.close-notification:hover {
        color: #f44336;
    }

    .btn-secondary {
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
            padding: 10px; 
            max-height: 60vh;
        }

        .notification-item {
            padding: 10px; 
        }
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myModal').on('show.bs.modal', function() {
            $.get('/api/notifications', function(data) {
                $('#notification-list').empty();

                data.forEach(function(notification) {
                    let notificationHtml = `
                        <div class="notification-item d-flex justify-content-between align-items-center mb-3" data-id="${notification.id_notifikasi}">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <p><strong>${notification.judul}</strong></p>
                                <p>${notification.isi_notifikasi}</p>
                            </div>
                            <button type="button" class="close close-notification" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    $('#notification-list').append(notificationHtml);
                });
                $('.close-notification').on('click', function() {
                    $(this).closest('.notification-item').remove();
                });
            });
        });

        // Delegasi event untuk menutup notifikasi dengan tombol 'x'
        // $(document).on('click', '.close-notification', function() {
        //     var notificationId = $(this).closest('.notification-item').data('id'); 

        // });
        // $(document).querySelectorAll('.close-notification').forEach(function(button) {
        //     button.addEventListener('click', function() {
        //         this.closest('.notification-item').remove();
        //     });
        // });

        // Arahkan ke halaman lain ketika "Lihat semua notifikasi" diklik
        $('.btn-secondary').on('click', function() {
            window.location.href = '/seeallnotif';
        });
    });
</script>

