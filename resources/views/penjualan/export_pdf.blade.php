<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img id="image" class="image" src="{{ asset('logo_polinema.jpg') }}">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA PENJUALAN</h3>
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>User ID</th>
                <th>Pembeli</th>
                <th>Kode Penjualan</th>
                <th>Tanggal Penjualan</th>
                <th>Barang ID</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp <!-- Inisialisasi variabel penghitung -->
            @foreach($penjualan as $penjualanItem)
                @foreach($penjualanItem->penjualan_detail as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td> <!-- Gunakan variabel penghitung -->
                        <td>{{ $penjualanItem->user_id }}</td>
                        <td>{{ $penjualanItem->pembeli }}</td>
                        <td>{{ $penjualanItem->penjualan_kode }}</td>
                        <td>{{ $penjualanItem->penjualan_tanggal }}</td>
                        <td>{{ $detail->barang_id }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->jumlah }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
