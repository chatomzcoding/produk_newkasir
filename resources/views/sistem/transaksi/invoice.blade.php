@extends('layouts.admin')

@section('title')
    Data Transaksi
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Transaksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('transaksi') }}">Daftar Transaksi</a></li>
            <li class="breadcrumb-item active">Invoice Transaksi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-balance-scale"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Bruto</span>
                  <span class="info-box-number">
                        {{ rupiah($invoice['bruto'])}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-balance-scale-right"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Netto</span>
                  <span class="info-box-number">
                        {{ rupiah($invoice['netto'])}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Laba</span>
                  <span class="info-box-number">
                        {{ rupiah($invoice['laba'])}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </div>
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                <form action="{{ url('transaksi') }}" method="get">
                  <button type="submit" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali ke daftar transaksi" id="kembali"><i class="fas fa-angle-left"></i> Kembali Ke Daftar Transaksi</button>
                </form>
              
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <div class="row">
                      <div class="col-md-8">
                          <h4><i class="fas fa-shopping-basket text-secondary"></i> Daftar Barang Belanjaan</h4>
                          <div class="table-responsive">
                              <table class="table table-bordered">
                                  <thead>
                                      <tr class="text-center">
                                          <th>No</th>
                                          <th>Nama Barang</th>
                                          <th>Harga Jual</th>
                                          <th>Jumlah</th>
                                          <th>Sub Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach (json_decode($transaksi->keranjang) as $item)
                                          <tr>
                                              <td class="text-center">{{ $loop->iteration }}</td>
                                              <td class="text-capitalize">{{ $item->nama_barang }}</td>
                                              <td class="text-right">{{ rupiah($item->harga_jual) }}</td>
                                              <td class="text-center">{{ $item->jumlah }}</td>
                                              <td>Rp <span class="float-right">{{ norupiah(subtotal($item->harga_jual,$item->jumlah)) }}</span></td>
                                          </tr>
                                      @endforeach
                                      <tr class="table-secondary">
                                          <th colspan="4" class="h4">Total Pembayaran</th>
                                          <td class="h4">Rp <span class="float-right">{{ norupiah($invoice['total_pembayaran']) }}</span></td>
                                      </tr>
                                      <tr>
                                          <th colspan="4" class="h4">Uang Pembeli</th>
                                          <td class="h4">Rp <span class="float-right">{{ norupiah($invoice['uang_pembeli']) }}</span></td>
                                      </tr>
                                      <tr class="bg-primary">
                                          <th colspan="4" class="h4">Kembalian</th>
                                          <td class="h4 font-weight-bold">Rp <span class="float-right">{{ norupiah($invoice['kembalian']) }}</span></td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                      <div class="col-md-4">
                        <h4><i class="fas fa-receipt text-secondary"></i> Transaksi {{ $transaksi->kode_transaksi}}</h4>
                          <div class="card">
                            <div class="card-body">
                                <section>
                                    <div class="table-responsive">
                                        <table width="100%">
                                          <tr>
                                              <th>Tanggal/Jam</th>
                                              <td>: {{ $transaksi->created_at }}</td>
                                          </tr>
                                          <tr>
                                              <th>Kasir</th>
                                              <td>: {{ $user->name }}</td>
                                          </tr>
                                        </table>
                                    </div>
                                </section>
                            </div>
                          </div>
                          <section class="mb-2">
                                <form action="{{ url('transaksi') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="sesi" value="tambah">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="kode_transaksi" value="{{ DbCikara::kodeTransaksi($user->id) }}">
                                    <input type="hidden" name="status_transaksi" value="proses">
                                    <input type="hidden" name="uang_pembeli" value="0">
                                    <button type="submit" class="btn btn-outline-primary btn-block text-left" id="tambahtransaksi"><i class="fas fa-plus"></i> TRANSAKSI BARU <span class="float-right">[Enter]</span></button>
                                </form>
                          </section>
                          <section class="mb-2">
                            <a href="" onclick="onClick()" class="btn btn-outline-info btn-block text-left" id="cetak"><i class="fas fa-print"></i> CETAK STRUK <span class="float-right">[P]</span></a>
                          </section>
                          <section class="mb-2">
                                <form action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="s" value="retur">
                                    <input type="hidden" name="status_transaksi" value="retur">
                                    <input type="hidden" name="uang_pembeli" value="{{ $invoice['total_pembayaran'] }}">
                                    <button type="submit" class="btn btn-outline-dark btn-block text-left" id="retur"><i class="fas fa-sync"></i> RETUR TRANSAKSI <span class="float-right">[R]</span></button>
                                </form>
                          </section>
                        </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    @section('script')

    <script src="{{ asset('js/recta.js')}}"></script>

<script type="text/javascript">
    // var printer = new Recta('{{ $client->app_key}}', '{{ $client->app_port}}')
    var printer = new Recta('8284151256', '1811')
    function onClick () {
      printer.open().then(function () {
          printer.align('center')
                .bold(true)
                .underline(false)
                .text('')
                .text('{{ strtoupper($client->nama_toko)}}')
                .bold(false)
                .text('selaawi')
                .text('--------------------------------')
                .align('left')
                .text('{{ date_indo(db_datetime($transaksi->created_at,'tgl')).' | '.db_datetime($transaksi->created_at,'jam')}}')
                .text('No. Trx: {{ $transaksi->kode_transaksi}}')
                .align('center')
                .text('--------------------------------')
                @foreach (json_decode($transaksi->keranjang) as $item)
                .align('left')
                .text('{{ $item->nama_barang}}')
                .align('right')
                .text('{{ $item->jumlah }} x {{norupiah($item->harga_jual).space('harga',$item->jumlah,$item->harga_jual).norupiah(subtotal($item->harga_jual,$item->jumlah))}}')
                @endforeach
                .text('--------------------------------')
                .align('right')
                .text('TOTAL{{ space('total',$invoice['total_pembayaran']).norupiah($invoice['total_pembayaran'])}}')
                .align('center')
                .align('right')
                .text('BAYAR{{ space('bayar',$transaksi->uang_pembeli).norupiah($transaksi->uang_pembeli)}}')
                .text('KEMBALIAN{{ space('kembalian',$invoice['kembalian']).norupiah($invoice['kembalian'])}}')
                .align('center')
                .text('--------------------------------')
                .align('left')
                .text('Kasir : {{ Auth::user()->name}}')
                .align('center')
                .text('')
                .text('Barang Yang Sudah Dibeli Tidak')
                .text('Dapat Dikembalikan/ditukar')
                .text('TERIMA KASIH ATAS KUNJUNGANNYA')
                // .underline(false)
                // .barcode('UPC-A', '123456789012')
                .cut()
            printer.raw([0x1b, 0x70, 0x00])
          .print()
      })
    }
  </script>
          <script type="text/javascript">
            function myFunction(){
                /* tombol enter */
                if(event.keyCode == 13) {
                    event.preventDefault()
                    $("#tambahtransaksi").click();
                }
                 /* tombol backspace */
                if(event.keyCode == 37) {
                    event.preventDefault()
                    $("#kembali").click();
                }
                 /* tombol R  */
                if(event.keyCode == 82) {
                    event.preventDefault()
                    $("#retur").click();
                }
                 /* tombol P */
                if(event.keyCode == 80) {
                    event.preventDefault()
                    $("#cetak").click();
                }
            } 
        </script>
    @endsection

    @endsection

