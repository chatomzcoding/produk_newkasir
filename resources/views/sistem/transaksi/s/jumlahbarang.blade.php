<section>
    <div class="card">
        <div class="card-body pb-0">
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
                    <label for="" class="col-md-10 h4 text-secondary"><i class="fas fa-plus"></i> Tambah Jumlah Barang <span class="text-dark">"{{ ucwords($_GET['nama_barang']) }}"</span></label>
                    <div class="col-md-2">
                        <input type="number" name="jumlah" class="form-control" value="{{ $_GET['jumlah'] }}" min="1" max="{{ $barang->stok }}"  @if ($s == 'jumlahbarang')
                        autofocus
                        @endif>
                        <small class="font-weight-bold">stok digudang {{ $barang->stok }}</small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>