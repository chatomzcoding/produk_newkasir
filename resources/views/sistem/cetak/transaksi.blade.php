<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Transaksi Cabang</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap4.3.1.css') }} " integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>Data Transaksi Cabang</h4>
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
                        <td>{{ $item->kode_transaksi}}</td>
                        <td>{{ $item->tipe_pembayaran}}</td>
                        <td>{{ $item->tipe_orderan}}</td>
                        <td>{{ rupiah($item->uang_pembeli)}}</td>
                        <td class="text-center">{{ $item->status_transaksi}}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Daftar Keranjang</th>
                        <td colspan="4">
                            @if (!is_null($item->keranjang))
                            <ul>
                                @foreach (json_decode($item->keranjang) as $key)
                                <li>
                                    {{ $key->nama_barang }}
                                    <span class="badge badge-secondary badge-pill">{{ $key->jumlah }}</span> <br>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="7">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>