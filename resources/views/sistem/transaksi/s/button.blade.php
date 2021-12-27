<div>
    {{-- button input barang melalui barcode --}}
    <section>
        <form action="{{ url('transaksi') }}" method="post">
            @csrf
            <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
            <input type="hidden" name="sesi" value="tambahbarang">
            <input type="hidden" name="status" value="barcode">
            <input type="text" name="kode_barcode" pattern="[0-9]+" placeholder="barcode disini" class="form-control" @if ($s == 'index')
                autofocus
            @endif required>
        </form>
    </section>

    {{-- button kembali ke daftar keranjang / halaman proses --}}
    <section class="mt-1">
        <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
            @csrf
            <input type="hidden" name="s" value="index">
            <button type="submit" class="btn btn-primary btn-block text-left" id="keranjang"><i class="fas fa-shopping-cart"></i> DAFTAR KERANJANG <span class="float-right">[F10]</span></button>
        </form>
    </section>

    {{-- button untuk mencari barang secara manual --}}
    <section class="mt-1">
        <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
            @csrf
            <input type="hidden" name="s" value="caribarang">
            <button type="submit" class="btn btn-info btn-block text-left" id="caribarang"><i class="fas fa-cart-plus"></i> TAMBAH KERANJANG <span class="float-right">[insert]</span></button>
        </form>
    </section>

    {{-- muncul ketika keranjang tidak kosong --}}
    @if ($data['totalpembayaran'] > 0)
        {{-- button pembayaran --}}
        <section class="mt-1">
            <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
                <input type="hidden" name="s" value="bayar">
                <button type="submit" class="btn btn-success btn-block text-left" id="klikbayar"><i class="fas fa-money-bill-wave"></i> BAYAR <span class="float-right">[-]</span></button>
            </form>
        </section>
        <section class="mt-1">
            <form id="data-{{ $transaksi->id }}" action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
                @csrf
                @method('delete')
            </form>
                <button onclick="deleteRow( {{ $transaksi->id }} )" class="btn btn-danger btn-block text-left" id="klikdelete"><i class="fas fa-trash"></i> HAPUS TRANSAKSI <span class="float-right">[Delete]</span></button>
        </section>
    @endif
</div>