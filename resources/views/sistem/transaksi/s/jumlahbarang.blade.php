<section>
    @php
        $barang = DbCikara::showtablefirst('barang',['kode_barang',$_GET['kode_barang']]); 
    @endphp
    <form action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="s" value="tambahjumlahbarang">
        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        <input type="hidden" name="kode_barang" value="{{ $_GET['kode_barang'] }}">
        <input type="hidden" name="nama_barang" value="{{ $barang->nama_barang}}">
        <div class="form-group row">
            <label for="" class="col-md-9 h4">Tambah Jumlah Barang "{{ $_GET['nama_barang'] }}"</label>
            <div class="col-md-3">
                <input type="number" name="jumlah" class="form-control" value="{{ $_GET['jumlah'] }}" min="1" max="{{ $barang->stok }}"  @if ($s == 'jumlahbarang')
                autofocus
                @endif>
                <small class="float-right">stok digudang {{ $barang->stok }}</small>
            </div>
        </div>
    </form>
</section>