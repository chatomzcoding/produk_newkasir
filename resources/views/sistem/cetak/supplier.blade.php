<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Supplier</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Supplier
            </h4>
            <h5>
                Cabang {{ ucwords($main['cabang']->nama_cabang) }}
            </h5>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Supplier</th>
                    <th>Telp</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody class="text-capitalize">
                @forelse ($supplier as $item)
                <tr>
                        <td class="text-center">{{ $loop->iteration}}</td>
                        <td>{{ $item->nama_supplier}}</td>
                        <td>{{ $item->telp}}</td>
                        <td>{{ $item->alamat}}</td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="4">tidak ada data</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>