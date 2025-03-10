@extends('adminlte::page')

@section('title', 'Jadwal Ruangan')

@section('content_header')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <h1>Pengelolaan dan Penjadwalan Ruangan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Kalender</h3>
                </div>

                <div class="card-body">
                    <div>
                        <div class="col-3">
                            <label for="calendarDate" class="form-check-label">Pilih Tanggal:</label>
                            <input type="date" id="calendarDate" class="form-control">
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
                <!-- Modal untuk memilih event -->
                <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalLabel">Pilih Event</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label for="eventSelect">Pilih Event:</label>
                                <select class="form-control" id="eventSelect" name="title">
                                    <!-- Options will be dynamically added -->
                                    <option value="seminar_1">Seminar 1</option>
                                    <option value="seminar_2">Seminar 2</option>
                                    <option value="seminar_3">Seminar 3</option>
                                    <option value="sidang">Sidang</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline/main.min.css">
@stop

@section('js')
    @vite('resources/js/app.js')
@stop
