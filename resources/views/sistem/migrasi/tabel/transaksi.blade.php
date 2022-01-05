<table class="table table-bordered table-striped">
    <thead class="text-center">
        <tr>
            <th width="5%">No</th>
            <th>Kode Transaksi</th>
            <th>Status pembeli</th>
            <th>status transaksi</th>
            <th>uang pembeli</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->kode_transaksi }}</td>
            <td>{{ $item->status_pembeli }}</td>
            <td>{{ $item->status_transaksi }}</td>
            <td>{{ $item->uang_pembeli }}</td>
        </tr>
    @endforeach
    </tbody>
</table>