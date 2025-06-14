@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
<h1>Daftar Kesediaan Membimbing</h1>

<div>
    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
    ['url' => '', 'label' => 'Daftar Kesediaan Membimbing']
    ]
    ])
    @endcomponent
</div>

@stop

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
<style>

    #filterSection {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    

    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    
    
    /* Filter groups spacing */
    .filter-group {
        margin-bottom: 8px;
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#toggleFilter').on('click', function () {
        $('#filterSection').slideToggle();
    });

    $(document).ready(function() {
     
        $('.select2').select2({
            width: '100%',
            minimumResultsForSearch: Infinity, // Removes the search box
            placeholder: "Pilih...",
            allowClear: true,
            dropdownCssClass: "select2-dropdown-clean"
        });
        
      
        var table = $('#kesediaanTable').DataTable({
            columns: [
                { orderable: true, searchable: true }, // No
                { orderable: false, searchable: false }, // KD DOSEN/ID DOSEN
                { orderable: true, searchable: true }, // NAMA
                { orderable: false, searchable: true }, // NIP
                { orderable: false, searchable: true }, // KBK
                { orderable: false, searchable: true }, // Status Pengumpulan
                @foreach (range(1, count($prodiList)) as $i)
                { orderable: false, searchable: false }, // Jumlah TA columns
                @endforeach
                @foreach (range(1, count($prodiList)) as $i)
                { orderable: false, searchable: true }, // Kesediaan Membimbing columns
                @endforeach
                { orderable: false, searchable: true } // Topik
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ entri keseluruhan)",
                loadingRecords: "Memuat...",
                zeroRecords: "Tidak ada data yang tersedia",
                emptyTable: "Tidak ada data yang tersedia",
                paginate: {
                    first: "<<",
                    last: ">>",
                    next: ">",
                    previous: "<"
                }
            }
        });
        
       
        $('#filterKBK').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            
            if (val === '') {
                table.column(4).search('').draw();
            } else {
                table.column(4).search('^' + val + '$', true, false).draw();
            }
        });
        
      
        $('#filterStatus').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            
            if (val === '') {
                table.column(5).search('').draw();
            } else {
                table.column(5).search('^' + val + '$', true, false).draw();
            }
        });



        $('#filterKesediaan').on('change', function() {
            applyKesediaanFilter();
        });

        
        function applyKesediaanFilter() {
            var kesediaanVal = $('#filterKesediaan').val();
            var prodiVal = $('#filterProdi').val();
            
           
            var baseJumlahIndex = 6;
            
           
            var startKesediaanIndex = baseJumlahIndex + {{ count($prodiList) }};
            
            // Clear any existing custom search functions
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

           
            if (kesediaanVal === '') {
                for (var i = 0; i < {{ count($prodiList) }}; i++) {
                    table.column(startKesediaanIndex + i).search('').draw(false);
                }
                table.draw();
                return;
            }
            
            
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
               
                if (!kesediaanVal) return true;
                
                // If a specific prodi is selected
                if (prodiVal && prodiVal !== '') {
               
                    var prodiIndex = -1;
                    @foreach($prodiList as $index => $prodi)
                        if ('{{ $prodi }}' === prodiVal) {
                            prodiIndex = {{ $index }};
                        }
                    @endforeach
                    
                  
                    if (prodiIndex >= 0) {
                        var kesediaanColIndex = startKesediaanIndex + prodiIndex;
                        
                        return data[kesediaanColIndex].trim() === kesediaanVal;
                    }
                    return false;
                } 
              
                else {
                    for (var i = 0; i < {{ count($prodiList) }}; i++) {
                        if (data[startKesediaanIndex + i].trim() === kesediaanVal) {
                            return true;
                        }
                    }
                    return false;
                }
            });
            
            table.draw();
        }
        
       
        $('#clearFilters').on('click', function() {
            
            $('#filterKBK, #filterStatus, #filterProdi, #filterKesediaan').val('').trigger('change');
            
            
            $('#kesediaanLabel').text('Kesediaan Membimbing:');
            
            
            table.search('').columns().search('').draw();
        });
    });
</script>
@stop

@section('content')


<div class="card p-2">
    <!-- Toggle Button -->
<div class="mb-2 filter-toggle-btn">
    <button id="toggleFilter" class="btn btn-secondary btn-sm">
        <i class="fas fa-filter"></i> 
    </button>
</div>

<!-- Filters Section -->
<div id="filterSection" class="mb-4" style="display: none;">
    <div class="row">
        <div class="col-md-12 mb-2">
            <h5 class="mb-0">Filter Data</h5>
            <hr class="mt-2 mb-3">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 filter-group">
            <label for="filterKBK" class="filter-label">KBK:</label>
            <select id="filterKBK" class="form-control select2">
                <option value="">Semua KBK</option>
                @foreach ($kbkOptions as $kbk)
                    <option value="{{ $kbk }}">{{ $kbk }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 filter-group">
            <label for="filterStatus" class="filter-label">Status Pengumpulan:</label>
            <select id="filterStatus" class="form-control select2">
                <option value="">Semua Status</option>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 filter-group">
            <label for="filterProdi" class="filter-label">Program Studi:</label>
            <select id="filterProdi" class="form-control select2">
                <option value="">Semua Prodi</option>
                @foreach ($prodiList as $prodi)
                    <option value="{{ $prodi }}">{{ $prodi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 filter-group">
            <label id="kesediaanLabel" for="filterKesediaan" class="filter-label">Kesediaan Membimbing:</label>
            <select id="filterKesediaan" class="form-control select2">
                <option value="">Semua</option>
                @foreach ($kesediaanOptions as $kesediaan)
                    <option value="{{ $kesediaan }}">{{ $kesediaan }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12 text-right">
            <button id="clearFilters" class="btn btn-outline-secondary btn-sm filter-button">
                <i class="fas fa-eraser"></i> Reset Filter
            </button>
        </div>
    </div>
</div>

    <table id="kesediaanTable" class="table table-responsive table-bordered w-100 table-striped">
        <thead>
            <tr class="bg-dark text-white">
                <th colspan="6" class="text-center">Dosen Eligible Sebagai Pembimbing 1</th>
                <th colspan="{{ count($prodiList) }}" class="text-center">Jumlah TA</th>
                <th colspan="{{ count($prodiList) }}" class="text-center">Kesediaan Membimbing</th>
                <th rowspan="2" class="text-center align-middle" style="min-width: 100vh;">Topik</th>
            </tr>
            <tr class="bg-dark text-white">
                <th class="text-center">No</th>
                <th class="text-center">KD DOSEN/ID DOSEN</th>
                <th class="text-center" style="min-width: 45vh;">NAMA</th>
                <th class="text-center" style="min-width: 20vh">NIP</th>
                <th class="text-center" style="min-width: 20vh">KBK</th>
                <th class="text-center" style="min-width: 10vh">Status Pengumpulan</th>
                @foreach ($prodiList as $prodi)
                <th class="text-center">{{ $prodi }}</th>
                @endforeach
                @foreach ($prodiList as $prodi)
                <th class="text-center">Kesediaan {{ $prodi }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @if (count($data) > 0)
            @foreach ($data as $item)
            <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                <td class="text-center align-middle">
                    {{ $item['kode_dosen'] }}<br>
                    {{ $item['id_dosen'] }}<br>
                </td>
                <td class="text-center align-middle">{{ $item['nama'] }}</td>
                <td class="text-center align-middle">{{ $item['nip'] }}</td>
                <td class="text-center align-middle">{{ $item['kbk'] }}</td>
                <td class="text-center align-middle">{{ $item['Status_Pengumpulan'] }}</td>

                @foreach ($prodiList as $prodi)
                <td class="text-center align-middle">{{ $item[$prodi] ?? '-' }}</td>
                @endforeach

                @foreach ($prodiList as $prodi)
                <td class="text-center align-middle">{{ $item['Kesediaan_' . $prodi] ?? '-' }}</td>
                @endforeach

                <td class="text-left truncate">{{ $item['bidang'] }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="{{ 6 + 2 * count($prodiList) + 1 }}" class="text-center">Tidak ada data</td>
            </tr>
            @endif
        </tbody>

    </table>
</div>

@stop