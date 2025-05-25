<x-adminlte-modal id="myModal" title="Notification" theme="info" size="md" icon="fas fa-bell" scrollable>

    <div class="d-flex justify-content-end align-items-center mb-2" style="margin-top: -15px;">
        <a href="#" id="openPreferences" class="btn btn-link p-0 mr-2" aria-label="Settings">
            <i class="fas fa-cog fa-lg"></i>
        </a>
    </div>

    <div id="notification-list"></div>

    <a href="{{ url('logUser') }}" class="btn btn-secondary w-100 mt-3">
        See All Notifications
    </a>

    <x-slot name="footerSlot"></x-slot> {{-- Footer kosong untuk hapus tombol default --}}
</x-adminlte-modal>


<x-adminlte-modal id="detailModal" title="Detail Notifikasi" theme="warning" icon="fas fa-info-circle" size="md" scrollable>
    <div class="modal-body">
        <h5 id="detail-title"></h5>
        <p id="detail-content" class="mb-1"></p>
        <p id="create-at" class="text-muted small"></p>
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Tutup" theme="secondary" data-dismiss="modal" />
    </x-slot>
</x-adminlte-modal>

<style>
    .notification-item {
        background-color: #e0e0e0;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 8px;
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
            $('#myModal').modal({
                backdrop: false,
                keyboard: false
            });
            loadNotifications();
        });

        $('#openPreferences').on('click', function(e) {
            e.preventDefault();
            $('#myModal').modal('hide');
            $('#myModals').modal({
                backdrop: false,
                keyboard: false
            });
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
                        $('#detailModal').modal({
                            backdrop: false,
                            keyboard: false
                        });
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