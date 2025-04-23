@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Stok</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Stok</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Stok</a>
                <button onclick="modalAction('{{ url('stok/create_ajax') }}')" class="btn btn-success">Tambah Stok</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter: </label>
                        <div class="col-3">
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Semua -</option>
                                @foreach($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Supplier</th>
                        <th>Nama Barang</th>
                        <th>Yang Bertanggung Jawab</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

    @push('css')
    @endpush

    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function () {
                    $('#myModal').modal('show');
                });
            }
            var dataStok;
            $(document).ready(function () {
                dataStok = $('#table_stok').DataTable({
                    // serverSide: true, jika ingin menggunakan server side processing 
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('stok/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function (d) {
                            d.supplier_id = $('#supplier_id').val();
                        }
                    },
                    columns: [
                        {  // nomor urut dari laravel datatable addIndexColumn() 
                            data: "DT_RowIndex",
                            className: "text-center",
                            width : "8%",
                            orderable: false,
                            searchable: false
                        }, {
                            data: "supplier.supplier_nama",
                            className: "",
                            // orderable: true, jika ingin kolom ini bisa diurutkan  
                            orderable: true,
                            // searchable: true, jika ingin kolom ini bisa dicari 
                            searchable: true
                        }, {
                            data: "barang.barang_nama",
                            className: "",
                            orderable: true,
                            searchable: true
                        }, {
                            // mengambil data level hasil dari ORM berelasi 
                            data: "user.username",
                            className: "",
                            orderable: false,
                            searchable: false
                        }, {
                            // mengambil data level hasil dari ORM berelasi 
                            data: "stok_tanggal",
                            className: "",
                            orderable: false,
                            searchable: false
                        },{
                            // mengambil data level hasil dari ORM berelasi 
                            data: "stok_jumlah",
                            className: "",
                            orderable: false,
                            searchable: false
                        },  {
                            data: "aksi",
                            className: "",
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
                $('#supplier_id').on('change', function () {
                    dataStok.ajax.reload();
                });
            }); 
        </script>
    @endpush