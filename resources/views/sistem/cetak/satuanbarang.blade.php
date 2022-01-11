<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Satuan Barang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Satuan Barang
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Satuan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($satuan as $item)
                <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td>{{ $item->nama}}</td>
                        <td>{{ $item->keterangan}}</td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="3">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>