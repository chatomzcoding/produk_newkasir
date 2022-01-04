<h4>Daftar Barang Dalam Keranjang</h4>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
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
                    $keranjang = json_decode($transaksi->keranjang,TRUE);
                    arsort($keranjang);
                @endphp
                @foreach ($keranjang as $item)
                    @php
                        $subtotal = $item['jumlah'] * $item['harga_jual'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-capitalize">{{ $item['nama_barang'] }}</td>
                        <td class="text-right">{{ norupiah($item['harga_jual']) }}</td>
                        <td class="text-center">
                            <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
                                <input type="hidden" name="s" value="jumlahbarang">
                                <input type="hidden" name="kode_barang" value="{{ $item['kode_barang'] }}">
                                <input type="hidden" name="nama_barang" value="{{ $item['nama_barang'] }}">
                                <input type="hidden" name="jumlah" value="{{ $item['jumlah'] }}">
                                @if ($loop->iteration == 1)
                                    <button type="submit" class="btn btn-primary btn-sm btn-block text-left" id="jumlahbarang">{{ $item['jumlah'] }} <span class="float-right"><i class="fas fa-plus"></i></span></button>
                                @else
                                    <button type="submit" class="btn btn-secondary btn-sm btn-block text-left" id="jumlahbarang">{{ $item['jumlah'] }} <span class="float-right"><i class="fas fa-mouse-pointer"></i></span></button>
                                @endif
                            </form>
                        </td>
                        <td class="text-right">{{ norupiah($subtotal) }}</td>
                        <td class="text-center">
                            <form action="{{url('transaksi/'.$transaksi->id)}}" method="post">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="s" value="hapusitem">
                                <input type="hidden" name="kode_barang" value="{{ $item['kode_barang'] }}">
                                @if ($loop->iteration == 1)
                                    <button type="submit" class="btn btn-danger btn-sm" id="hapusitem"><i class="fas fa-trash"></i></button>
                                @else
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-trash"></i></button>
                                @endif
                            </form>
                                
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>