<!DOCTYPE html>
<html>
<head>
    <title>Print PDF</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Daftar Pembelian Sekolah</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pembelian</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Pembeli</th>
                <th>Toko</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($schoolPurchases as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->tanggal_pembelian }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->harga_satuan }}</td>
                    <td>{{ $item->jumlah_baik+$item->jumlah_rusak }}</td>
                    <td>{{ $item->total_harga }}</td>
                    <td>{{ $item->pembeli }}</td>
                    <td>{{ $item->toko }}</td>
                    <td>{{ $item->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
