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
            <li class="breadcrumb-item active">Proses Transaksi - {{ $transaksi->kode_transaksi }}</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
            <div class="col-md-12">
                @include('sistem.notifikasi')
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <form action="{{ url('transaksi') }}" method="get">
                                    <button type="submit" class="btn btn-outline-secondary btn-flat btn-sm pop-info" id="kembali"><i class="fas fa-angle-left"></i> Kembali</button> 
                                    <div class="float-right">
                                        {!! statustransaksi($transaksi->status_transaksi) !!}
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <section>
                                    <form action="{{ url('transaksi') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                                        <input type="hidden" name="sesi" value="tambahbarang">
                                        <input type="hidden" name="status" value="barcode">
                                        <input type="text" name="kode_barcode" pattern="[0-9]+" placeholder="barcode disini" class="form-control" @if ($s == 'index')
                                            autofocus
                                        @endif>
                                    </form>
                                </section>
                                <section class="mt-2">
                                    <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
                                        @csrf
                                        <input type="hidden" name="s" value="index">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block" id="keranjang"><i class="fas fa-shopping-cart"></i> KERANJANG <span class="float-right">[F10]</span></button>
                                    </form>
                                </section>
                                <section class="mt-2">
                                    <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
                                        @csrf
                                        <input type="hidden" name="s" value="caribarang">
                                        <button type="submit" class="btn btn-info btn-sm btn-block" id="caribarang"><i class="fas fa-search"></i> CARI BARANG <span class="float-right">[insert]</span></button>
                                    </form>
                                </section>
                                {{-- muncul ketika keranjang tidak kosong --}}
                                @if ($data['totalpembayaran'] > 0)
                                    <section class="mt-2">
                                        <form action="{{ url('transaksi/'.Crypt::encryptString($transaksi->id)) }}" method="get">
                                            <input type="hidden" name="s" value="bayarnominal">
                                            <button type="submit" class="btn btn-secondary btn-sm btn-block" id="klikbayar"><i class="fas fa-money-bill-wave"></i> BAYAR NOMINAL <span class="float-right">[-]</span></button>
                                        </form>
                                    </section>
                                    <section class="mt-2">
                                        <form action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="s" value="bayarpas">
                                            <input type="hidden" name="uang_pembeli" value="{{ totalpembayaran($transaksi->keranjang) }}">
                                            <button type="submit" class="btn btn-success btn-sm btn-block" id="klikselesai"><i class="fas fa-money-bill-wave"></i> BAYAR PAS <span class="float-right">[Shift R]</span></button>
                                        </form>
                                    </section>
                                    <section class="mt-2">
                                        <form id="data-{{ $transaksi->id }}" action="{{ url('transaksi/'.$transaksi->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>
                                            <button onclick="deleteRow( {{ $transaksi->id }} )" class="btn btn-danger btn-sm btn-block" id="klikdelete"><i class="fas fa-trash"></i> HAPUS TRANSAKSI <span class="float-right">[Delete]</span></button>
                                    </section>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header p-1 bg-info">
                                        TOTAL PEMBAYARAN
                                    </div>
                                    <div class="card-body display-3 p-0 text-right">
                                        <strong>Rp. {{ norupiah($data['totalpembayaran']) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        @switch($s)
                                            @case('caribarang')
                                                @include('sistem.transaksi.s.caribarang')
                                                @break
                                            @case('bayarnominal')
                                                @include('sistem.transaksi.s.bayarnominal')
                                                @break
                                            @case('jumlahbarang')
                                                @include('sistem.transaksi.s.jumlahbarang')
                                                @break
                                            @default
                                                
                                        @endswitch
                                        @include('sistem.transaksi.keranjang')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="{{ asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('js/jquery.autocomplete.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // Selector input yang akan menampilkan autocomplete.
            $( "#nama" ).autocomplete({
                serviceUrl: "{{route('loadBarang')}}",   // Kode php untuk prosesing data.
                dataType: "JSON",           // Tipe data JSON.
                onSelect: function (suggestion) {
                    $( "#nama" ).val("" + suggestion.nama);
                }
            });
        });

        $("#benih").on("change", function(){
        // ambil nilai
        var harga = $("#benih option:selected").attr("harga");

        // pindahkan nilai ke input
        $("#harga").val(harga);

        });
    </script>
    <script type="text/javascript">
        function myFunction(){
            /* tombol backspace <- */
            if(event.keyCode == 37) {
                event.preventDefault()
                $("#kembali").click();
            }
            /* tombol - */
            if(event.keyCode == 189) {
                event.preventDefault()
                $("#klikbayar").click();
            }
            /* tombol Delete */
            if(event.keyCode == 46) {
                event.preventDefault()
                $("#klikdelete").click();
            }
            /* tombol shift R */
            if(event.keyCode == 16) {
                event.preventDefault()
                $("#klikselesai").click();
            }
            /* tombol insert */
            if(event.keyCode == 45) {
                event.preventDefault()
                $("#caribarang").click();
            }
            /* tombol + */
            if(event.keyCode == 107) {
                event.preventDefault()
                $("#jumlahbarang").click();
            }
            /* tombol / */
            if(event.keyCode == 191) {
                event.preventDefault()
                $("#cariharga").click();
            }
            /* tombol \ */
            if(event.keyCode == 220) {
                event.preventDefault()
                $("#hapusitem").click();
            }
            /* tombol F10 */
            if(event.keyCode == 121) {
                event.preventDefault()
                $("#keranjang").click();
            }
        }
    </script>
    @endsection
