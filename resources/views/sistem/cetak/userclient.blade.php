<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data User Client</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data User Client
            </h4>
            <hr>
        </section>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user as $item)
                    <tr class="text-center">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-capitalize">{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center text-uppercase">{{ $item->level }} </td>
                    </tr>
                @endforeach
        </table>
	</div>
</body>
</html>