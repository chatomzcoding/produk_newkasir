<section>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Barang</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-right">Sub Total</th>
                    <th class="text-center"><i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                @if (is_null($transaksi->keranjang))
                    <tr>
                        <td colspan="7" class="text-center font-italic">belum ada barang dalam keranjang</td>
                    </tr>
                @else
                    @php
                        $keranjang = json_decode($transaksi->keranjang)
                    @endphp
                    @foreach ($keranjang as $item)
                        @php
                            $subtotal = $item->jumlah * $item->harga_jual;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-right">{{ norupiah($item->harga_jual) }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right">{{ norupiah($subtotal) }}</td>
                            <td class="text-center">
                                @if ($loop->iteration == 1)
                                <form action="{{url('transaksi/'.$transaksi->id)}}" method="post">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="s" value="hapusitem">
                                    <input type="hidden" name="kode_barang" value="{{ $item->kode_barang }}">
                                    <button type="submit" class="btn btn-danger btn-sm" id="hapusitem"><i class="fas fa-trash"></i></button>
                                </form>
                                @else
                                    
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</section>