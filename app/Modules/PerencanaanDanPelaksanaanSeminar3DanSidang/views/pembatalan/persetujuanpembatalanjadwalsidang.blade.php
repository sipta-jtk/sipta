
<td>{{ $s->nama_kota }}</td>
                <td>{{ $s->judul_ta }}</td>
                <td>{{ $s->tanggal }}</td>
                <td>{{ $s->sesi }}</td>
                <td>{{ $s->id_ruangan }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->alasan_pembatalan }}</td>
                <td>
                    @if ($s->status_pembatalan == 0)
                        <span>Pembatalan Ditolak</span>
                    @elseif ($s->status_pembatalan == 1)
                        <span>Pembatalan Disetujui</span>
                    @endif
                </td>
                <td>
                    @if ($s->status_pembatalan == 1 || $s->status_pembatalan == 0)
                    <form
                        action="{{ route('persetujuan.pembatalan.seminar', ['pembatalan_id' => $s->id_pembatalan, 'status' => 1]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-md w-100 my-1" disabled>Setuju</button>
                    </form>
                    <form
                        action="{{ route('persetujuan.pembatalan.seminar', ['pembatalan_id' => $s->id_pembatalan, 'status' => 0]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-md w-100 my-1" disabled>Tolak</button>
                    </form>
                    @else
                    <form
                        action="{{ route('persetujuan.pembatalan.seminar', ['pembatalan_id' => $s->id_pembatalan, 'status' => 1]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-md w-100 my-1">Setuju</button>
                    </form>
                    <form
                        action="{{ route('persetujuan.pembatalan.seminar', ['pembatalan_id' => $s->id_pembatalan, 'status' => 0]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-md w-100 my-1">Tolak</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#seminarTable').DataTable();
    });
</script>
@stop