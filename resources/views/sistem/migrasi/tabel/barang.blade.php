<table class="table table-bordered table-striped">
    <thead class="text-center">
        <tr>
            <th width="5%">No</th>
            <th>Kode Barang</th>
            <th>Kode Barcode</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Kategori</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->kode_barang }}</td>
            <td>{{ $item->kode_barcode }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->stok.' '.$item->satuan_barang }}</td>
            <td>{{ $item->harga_beli }}</td>
            <td>{{ $item->harga_jual }}</td>
            <td>{{ $item->kategori }}</td>
            <td>{{ $item->status_barang }}</td>
        </tr>
    @endforeach
    </tbody>
</table>