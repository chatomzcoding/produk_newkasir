<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Karyawan</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Daftar Karyawan <br> Cabang {{ $main['cabang']->nama_cabang }}
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Karyawan</th>
                    <th>Email</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($user as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-capitalize">{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center text-uppercase">{{ $item->level }} </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Data Belum Ada</td>
                    </tr>
                @endforelse
        </table>
	</div>
</body>
</html>