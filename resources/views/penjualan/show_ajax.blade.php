@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5>
                    Berikut adalah detail data Penjualan:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pembuat :</th>
                        <td class="col-9">{{ $penjualan->user->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pembeli :</th>
                        <td class="col-9">{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Penjualan :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i:s') }}</td>
                    </tr>                    
                </table>
                
                <table class="table table-sm table-bordered">
                    <thead>
                         <tr>
                              <th>Gambar Barang</th>
                              <th>Nama Barang</th>
                              <th>Harga</th>
                              <th>Jumlah</th>
                              <th>Total Harga</th>
                         </tr>
                    </thead>
                    <tbody>
                         @foreach($penjualan->penjualan_detail as $detail)
                         <tr>  
                              <td>
                                  <img src="{{ $detail->barang->avatar ? asset($detail->barang->avatar) : asset('barang_default.png') }}" 
                                       alt="Gambar Barang" style="width: 100px; height: auto;">
                              </td>
                              <td>{{ $detail->barang->barang_nama }}</td>
                              <td>{{ number_format($detail->harga, 2) }}</td>
                              <td>{{ $detail->jumlah }}</td>
                              <td>{{ number_format($detail->harga * $detail->jumlah, 2) }}</td>
                         </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty  
