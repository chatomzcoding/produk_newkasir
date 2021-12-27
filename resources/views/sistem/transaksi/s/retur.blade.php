<section>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Batas Pembayaran Sebelumnya</strong>
                </div>
                <div class="card-body">
                    {{ $transaksi->uang_pembeli }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Sisa Pembayaran</strong>
                </div>
                <div class="card-body">
                    {{ $data['sisapembayaran'] }}
                </div>
            </div>
        </div>
    </div>
</section>