<table class="table table-bordered table-striped">
    <thead class="text-center">
        <tr>
            <th width="5%">No</th>
            <th>No Faktur</th>
            <th>Tgl Faktur</th>
            <th>Tgl Tempo</th>
            <th>Pembayaran</th>
            <th>Potongan</th>
            <th>Status Stok</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->no_faktur }}</td>
            <td>{{ $item->tgl_faktur }}</td>
            <td>{{ $item->tgl_tempo }}</td>
            <td>{{ $item->pembayaran }}</td>
            <td>{{ $item->potongan }}</td>
            <td>{{ $item->status_stok }}</td>
        </tr>
    @endforeach
    </tbody>
</table>