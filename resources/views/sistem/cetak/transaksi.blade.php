<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Transaksi <br>
                {{ date_indo($tanggal) }} <br>
                Kasir : {{ $data['nama_kasir'] }}
            </h4>
            <hr>
        </section>
        <table class="table table-bordered table-striped small">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Kode Transaksi</th>
                    <th>Tipe Pembayaran</th>
                    <th>Tipe Orderan</th>
                    <th>Uang Pembeli</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($transaksi as $item)
                <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td> <small>{{ $item->kode_transaksi}}</small></td>
                        <td>{{ $item->tipe_pembayaran}}</td>
                        <td>{{ $item->tipe_orderan}}</td>
                        <td>{{ rupiah($item->uang_pembeli)}}</td>
                        <td class="text-center">{{ $item->status_transaksi}}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Daftar Keranjang</th>
                        <td colspan="4">
                            @if (!is_null($item->keranjang))
                                @foreach (json_decode($item->keranjang) as $key)
                                    - {{ ucwords($key->nama_barang) }} ({{ $key->jumlah }}) <br>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="6">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>