<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Barang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Barang <br>
                kategori : {{ DbCikara::namaKategori($kategori) }}
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center" style="font-size: 11px">
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody  style="font-size: 11px">
                @foreach ($barang as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ ucwords($item->nama_barang) }}</td>
                        <td class="text-right">{{ norupiah($item->harga_beli) }} </td>
                        <td class="text-right">{{ norupiah($item->harga_jual) }} </td>
                        <td class="text-center">{{ DbCikara::namaKategori($item->kategori_id) }}</td>
                        <td class="text-center">{{ $item->stok }} </td>
                    </tr>
                @endforeach
        </table>
	</div>
</body>
</html>