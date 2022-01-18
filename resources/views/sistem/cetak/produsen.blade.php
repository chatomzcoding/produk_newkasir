<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Produsen</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Produsen
            </h4>
            <h5>
                Cabang {{ ucwords($main['cabang']->nama_cabang) }}
            </h5>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Nama Produsen</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($produsen as $item)
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