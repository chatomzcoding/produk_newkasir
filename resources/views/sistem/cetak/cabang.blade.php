<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Cabang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Daftar Cabang
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Cabang</th>
                    <th>Alamat</th>
                    <th>Pimpinan</th>
                    <th>No Telp</th>
                    <th>Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($cabang as $item)
                <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td>{{ $item->nama_cabang}}</td>
                        <td>{{ $item->alamat}}</td>
                        <td>{{ $item->pimpinan}}</td>
                        <td>{{ $item->telp}}</td>
                        <td>{{ date_indo($item->tgl_gabung)}}</td>
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