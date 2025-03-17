
<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}">
    Edit
</button>

<!-- Modal -->
<div class="modal fade" id="editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}" tabindex="-1" aria-labelledby="editNotifikasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Notifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('notifikasi.update', $notifikasi->id_template_notifikasi) }}" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="form-group">
                        <label for="judul_notifikasi">Judul Notifikasi</label>
                        <x-adminlte-input name="judul_notifikasi" value="{{ $notifikasi->judul_notifikasi }}" required />
                    </div>

                    <div class="form-group">
                        <label for="jenis_notifikasi">Jenis Notifikasi</label>
                        <x-adminlte-select name="jenis_notifikasi" required>
                            <option value="pemberitahuan" {{ $notifikasi->jenis_notifikasi == 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                            <option value="reminder" {{ $notifikasi->jenis_notifikasi == 'reminder' ? 'selected' : '' }}>Reminder</option>
                        </x-adminlte-select>
                    </div>

                    <div class="form-group">
                        <label for="isi_in_apps">Template In Apps</label>
                        <x-adminlte-textarea name="isi_in_apps" rows="4s" required>{{ $notifikasi->isi_in_apps }}</x-adminlte-textarea>
                    </div>

                    <div class="form-group">
                        <label for="isi_in_email">Template In Email</label>
                        <x-adminlte-textarea name="isi_in_email" rows="4" required>{{ $notifikasi->isi_in_email }}</x-adminlte-textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
