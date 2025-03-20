@extends('adminlte::page')

@section('title', 'RepositoryTA')

@section('content_header')
<h1>RepositoryTA</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Kelompok TA</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0">No</th>
                                <th class="sorting" tabindex="0">KoTA</th>
                                <th class="sorting" tabindex="0">Judul TA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01</td>
                                <td>Implementasi Web Design</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>02</td>
                                <td>Implementasi Web Service</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>03</td>
                                <td>Pemrograman Mobile</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>04</td>
                                <td>Machine Learning Dasar</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>05</td>
                                <td>Keamanan Jaringan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<!-- DataTables & Plugins -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
});
</script>
@stop
