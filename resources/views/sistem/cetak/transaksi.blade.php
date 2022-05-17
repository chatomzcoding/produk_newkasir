<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Transaksi <br>
                {{ $data['info'] }} <br>
                {{ $data['sesi'] }}
            </h4>
            <hr>
        </section>
        <table class="table table-bordered table-striped small">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Kode Transaksi</th>
                    <th>Uang Pembeli</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($transaksi as $item)
                <tr>
                        <th class="text-center">{{ $loop->iteration}}</th>
                        <th class="text-leftW"> <small>{{ $item->kode_transaksi}}</small></th>
                        <td class="text-right">{{ norupiah($item->uang_pembeli)}}</td>
                    </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                @if (!is_null($item->keranjang))
                                    @foreach (json_decode($item->keranjang) as $key)
                                        - {{ strtolower($key->nama_barang) }} ({{ $key->jumlah }}) <br>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="5">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>