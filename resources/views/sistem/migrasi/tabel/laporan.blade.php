<table class="table table-bordered table-striped">
    <thead class="text-center text-uppercase">
        <tr>
            <th width="5%">No</th>
            <th>Tgl Laporan</th>
            <th>transaksi</th>
            <th>penjualan</th>
            <th>modal</th>
            <th>pengambilan</th>
            <th>laba</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->tgl_laporan }}</td>
            <td class="text-center">{{ $item->total_transaksi }}</td>
            <td class="text-right">{{ norupiah($item->total_penjualan) }}</td>
            <td class="text-right">{{ norupiah($item->total_modal) }}</td>
            <td class="text-right">{{ norupiah($item->total_pengambilan) }}</td>
            <td class="text-right">{{ norupiah($item->total_laba) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>