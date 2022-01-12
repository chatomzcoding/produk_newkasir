<!DOCTYPE html>
<html>
<head>
	<title>Cetak Detail Barang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Detail Barang - {{ ucwords($barang->nama_barang) }}
            </h4>
        </section>
        <section>
            <table class="table">
                <tr>
                    <th width="30%">Kode Barang</th>
                    <td>{{ $barang->kode_barang }}</td>
                </tr>
                <tr>
                    <th>Kode Barcode</th>
                    <td>{{ $barang->kode_barcode }}</td>
                </tr>
                <tr>
                    <th>Kategori Barang</th>
                    <td class="text-uppercase">{{ DbCikara::namaKategori($barang->kategori_id) }}</td>
                </tr>
                <tr>
                    <th>Harga Beli</th>
                    <td>{{ rupiah($barang->harga_beli) }}</td>
                </tr>
                <tr>
                    <th>Harga Jual</th>
                    <td>{{ rupiah($barang->harga_jual) }}</td>
                </tr>
                <tr>
                    <th>Stok Barang</th>
                    <td>{{ $barang->stok }}</td>
                </tr>
                <tr>
                    <th>Status Barang</th>
                    <td class="text-uppercase">{{ $barang->status_barang }}</td>
                </tr>
                <tr>
                    <th>Merk Barang</th>
                    <td>{{ $barang->merk }}</td>
                </tr>
            </table>
        </section>
	</div>
</body>
</html>