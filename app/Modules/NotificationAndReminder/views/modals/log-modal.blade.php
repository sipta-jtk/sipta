<!-- Modal untuk Notifikasi -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="notificationModalLabel">Notification</h3>
        <div>
          <a href="#" id="openPreferences" class="btn btn-link p-0 mr-2" aria-label="Settings">
            <i class="fas fa-cog"></i>
          </a>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div id="notification-list"></div>
      <a href="user/log-user" class="btn btn-secondary w-100">See All Notifications</a>
    </div>
  </div>
</div>

<!-- Modal untuk Detail Notifikasi -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="detailModalLabel">Detail Notifikasi</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 id="detail-title"></h5>
        <p id="detail-content"></p>
        <p id="create-at"></p>
      </div>
    </div>
  </div>
</div>


<style>
    .modal {
        background: none;
        /* pointer-events: none;  */
    }
    .modal-dialog {
        pointer-events: all;
        margin-top: 60px; 
    }
    #openPreferences i {
        color: #888; 
    }

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
    .notification-item {
        background-color: #e0e0e0;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 8px;
        width: 100%;
        cursor: pointer;
        position: relative;
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
    }
    .close-notification {
        background: none;
        border: none;
        font-size: 20px;
        color: #888;
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    .close-notification:hover {
        color: #f44336;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#notificationBell').on('click', function(e) {
            e.preventDefault();
            $('#myModal').modal({ backdrop: false, keyboard: false });
            loadNotifications();
        });
        
        $('#openPreferences').on('click', function(e) {
            e.preventDefault();
            $('#myModal').modal('hide'); 
            $('#myModals').modal({ backdrop: false, keyboard: false });
        });

        $(document).on('click', function(event) {
            var target = $(event.target);
            if ($('#myModal').is(':visible') && !target.closest('.modal-dialog').length && !target.closest('#notificationBell').length) {
                $('#myModal').modal('hide');
            }
        });

        function loadNotifications() {
            $.get('/sipta/api/notifications', function(data) {
                $('#notification-list').empty();
                if (data.length === 0) {
                    $('#notification-list').append('<div class="text-center">Tidak ada notifikasi baru</div>');
                } else {
                    data.forEach(function(notification) {
                        let html = `
                            <div class="notification-item" data-id="${notification.id_notifikasi}">
                                <i class="fas fa-exclamation-circle"></i>
                                <div><p><strong>${notification.judul}</strong></p></div>
                                <button type="button" class="close-notification" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`;
                        $('#notification-list').append(html);
                    });
                }
            })
            }
        

        $(document).on('click', '.notification-item', function(event) {
            if (!$(event.target).hasClass('close-notification')) {
                let notificationId = $(this).data('id');
                $.get(`/sipta/api/notifications/${notificationId}`, function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        $('#detail-title').text(data.judul);
                        $('#detail-content').text(data.isi_notifikasi);
                        $('#create-at').text(data.created_at);
                        $('#detailModal').modal('show');
                    }
                });
            }
        });

        $(document).on('click', '.close-notification', function(event) {
            event.stopPropagation();
            $(this).closest('.notification-item').remove();
        });

    });
</script>
