@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-sm btn-info mt-1">
                    <i class="fas fa-file-import"></i> Import Stok
                </button> 
                <a href="{{url('/stok/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Stok</a>
                <a href="{{url('/stok/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Stok</a>
                <button onclick="modalAction('{{ url('stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fas fa-box"></i> Tambah Stok
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (@session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-1">Filter:</label>
                        <div class="col">
                            <select class="form-control" id="suppllier_id" name="suppllier_id">
                                <option value="">- Semua -</option>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->suppllier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier Barang</small>
                        </div>
                        <div class="col">
                            <select class="form-control" id="barang_id" name="barang_id">
                                <option value="">- Semua -</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Barang</small>
                        </div>
                        <div class="col">
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">- Semua -</option>
                                @foreach ($user as $u)
                                    <option value="{{ $u->user_id }}">{{ $u->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Pembuat</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>Nama Barang</th>
                            <th>Nama Pegawai</th>
                            <th>Tanggal Stok </th>
                            <th>Jumlah Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        $(document).ready(function() {
            dataStok = $('#table_stok').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.suppllier_id = $('#suppllier_id').val();
                        d.barang_id = $('#barang_id').val();
                        d.user_id = $('#user_id').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "supplier.supplier_nama",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang.barang_nama",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "user.nama",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "stok_tanggal",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "stok_jumlah",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload table data on filter change
            $('#suppllier_id, #barang_id, #user_id').on('change', function() {
                dataStok.ajax.reload();
            });
        });
    </script>
@endpush
