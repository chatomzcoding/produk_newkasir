<table class="table table-bordered table-striped">
    <thead class="text-center">
        <tr>
            <th width="5%">No</th>
            <th>Nama Supplier</th>
            <th>Telp</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_supplier }}</td>
            <td>{{ $item->telp }}</td>
            <td>{{ $item->alamat }}</td>
        </tr>
    @endforeach
    </tbody>
</table>