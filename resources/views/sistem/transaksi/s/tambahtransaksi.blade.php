<section>
    <div class="alert alert-warning">
       <strong> Ingin menambahkan transaksi baru ? </strong> <br>
        Transaksi sebelumnya akan tersimpan ditab yang berbeda
    </div>
</section>
<section class="text-center">
    <form action="{{ url('transaksi') }}" method="post" target="_blank">
        @csrf
        <input type="hidden" name="sesi" value="tambah">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input type="hidden" name="kode_transaksi" value="{{ DbCikara::kodeTransaksi($user->id) }}">
        <input type="hidden" name="status_transaksi" value="proses">
        <input type="hidden" name="uang_pembeli" value="0">
        <button type="submit" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Transaksi" id="tambahtransaksi"><i class="fas fa-plus"></i> Tambah Transaksi Baru [Enter]</button>
    </form>
</section>