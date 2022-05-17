<section>
    <h2>Pilih Nominal Pembayaran</h2>
    <form action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
        @csrf
        @method('patch')
        <input type="hidden" name="s" value="bayar">
        <input type="hidden" name="uangretur" value="{{ $transaksi->uang_pembeli }}">
        <div class="row mb-1">
            <div class="col-md-12 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="{{ $data['totalpembayaran'] }}" id="bayarpas" checked>
                      </span>
                    </div>
                    <input type="text" value="{{ $data['totalpembayaran'] }} | BAYAR PAS SESUAI DENGAN TOTAL PEMBAYARAN" class="form-control" readonly>
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="radio" name="nominal" value="2000" id="2000" {{ disablenominal($data['totalpembayaran'],2000) }}>
                        </span>
                    </div>
                    <input type="text" value="2.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F1</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="5000" id="5000"  {{ disablenominal($data['totalpembayaran'],5000) }}>
                      </span>
                    </div>
                    <input type="text" value="5.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F2</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="10000" id="10000"  {{ disablenominal($data['totalpembayaran'],10000) }}>
                      </span>
                    </div>
                    <input type="text" value="10.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F3</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="20000" id="20000"  {{ disablenominal($data['totalpembayaran'],20000) }}>
                      </span>
                    </div>
                    <input type="text" value="20.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F4</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="50000" id="50000"  {{ disablenominal($data['totalpembayaran'],50000) }}>
                      </span>
                    </div>
                    <input type="text" value="50.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F5</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="100000" id="100000"  {{ disablenominal($data['totalpembayaran'],100000) }}>
                      </span>
                    </div>
                    <input type="text" value="100.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F6</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="150000" id="150000"  {{ disablenominal($data['totalpembayaran'],150000) }}>
                      </span>
                    </div>
                    <input type="text" value="150.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F7</div>
                    </div>
                  </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="radio" name="nominal" value="200000" id="200000"  {{ disablenominal($data['totalpembayaran'],200000) }}>
                      </span>
                    </div>
                    <input type="text" value="200.000" class="form-control" readonly>
                    <div class="input-group-append">
                        <div class="input-group-text bg-info">F8</div>
                    </div>
                  </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8 p-1">
                <input type="text" name="nominalmanual" class="form-control" placeholder="masukkan nominal secara manual" autofocus>
            </div>
            <div class="col-md-4 p-1">
                <button type="submit" class="btn btn-success btn-block text-left" id="bayarsekarang">BAYAR SEKARANG <span class="float-right">[Shift R]</span></button>
            </div>
        </div>
    </form>
</section>