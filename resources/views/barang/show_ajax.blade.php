@empty($barang)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Detail Informasi</h5>
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Barang ID :</th>
                        <td class="col-9">{{ $barang->barang_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Barang :</th>
                        <td class="col-9">{{ $barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kategori Barang :</th>
                        <td class="col-9">{{ $barang->kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Barang :</th>
                        <td class="col-9">{{ $barang->barang_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Harga Jual :</th>
                        <td class="col-9" id="harga_jual">{{ $barang->harga_jual }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Harga Beli :</th>
                        <td class="col-9" id="harga_beli">{{ $barang->harga_beli }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Gambar Barang :</th>
                        <td class="col-9" id="avatar">
                            <img src="{{ $barang->avatar ? asset($barang->avatar) : asset('barang_default.png') }}" 
                                 alt="Gambar Barang" style="width: 100px; height: auto;">
                        </td>
                    </tr>
                </table>
                <div class="modal-footer"> 
                    <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button> 
                </div>
            </div>
        </div>
    </div>
    <script>
        function formatRupiah(angka) {
            let numberString = angka.toString();
            let sisa = numberString.length % 3;
            let rupiah = numberString.substr(0, sisa);
            let ribuan = numberString.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp ' + rupiah;
        }

        $(document).ready(function() {
            // Dapatkan nilai harga jual dan harga beli dari td
            let hargaJual = $('#harga_jual').text();
            let hargaBeli = $('#harga_beli').text();

            // Gantikan dengan format rupiah
            $('#harga_jual').text(formatRupiah(hargaJual));
            $('#harga_beli').text(formatRupiah(hargaBeli));
        });
    </script>
@endempty
