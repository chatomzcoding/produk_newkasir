<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Barang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Barang
            </h4>
            @if ($data['kategori'] <> 'semua')
                <h5>
                    kategori : {{ ucwords($dkategori->nama) }} <br> Cabang {{ ucwords($main['cabang']->nama_cabang) }}
                </h5>
            @endif
        </section>
        <table class="table">
            <thead class="text-center" style="font-size: 11px">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode</th>
                    <th>Nama Barang</th>
                    <th width="15%">Harga Beli</th>
                    <th width="15%">Harga Jual</th>
                    @if ($data['kategori'] == 'semua')
                    <th>Kategori</th>
                    @endif
                    <th width="10%">Stok</th>
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
                        @if ($data['kategori'] == 'semua')
                        <td class="text-center">{{ DbCikara::namaKategori($item->kategori_id) }}</td>
                        @endif
                        <td class="text-center">{{ $item->stok }} </td>
                    </tr>
                @endforeach
        </table>
	</div>
</body>
</html>