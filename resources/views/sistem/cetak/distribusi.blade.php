<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Distribusi</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Distribusi <br>
                {{ $data['info'] }}
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>No Faktur</th>
                    <th>Tanggal Faktur</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="text-capitalize small">
                @forelse ($datatabel as $item)
                <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td>{{ $item->kode_distribusi}}</td>
                        <td>{{ $item->no_faktur}}</td>
                        <td>{{ $item->tgl_faktur}}</td>
                        <td class="text-center text-uppercase">{{ $item->pembayaran}}</td>
                        <td class="text-center">{!! showstatus($item->status_stok)!!}</td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="6" class="text-center">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>