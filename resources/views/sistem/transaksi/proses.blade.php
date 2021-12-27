@extends('layouts.kasir')

@section('title')
    Data Transaksi
@endsection

@section('header')
    <div class="row">
        <div class="col-md-12">
            <div class="card my-2">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1>TRANSAKSI KASIR</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <strong>Toko {{ $client->nama_toko }} | {{ date_indo(tgl_sekarang()) }} 
                                    <span id="jam"></span>:<span id="menit"></span>:<span id="detik"></span> </strong>
                            <br>
                            Kasir : <i>{{ $user->name }}</i>
                        </div><!-- /.col -->
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
@endsection
   
@section('container')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @include('sistem.notifikasi')
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <form action="{{ url('transaksi') }}" method="get">
                                                <button type="submit" class="btn btn-outline-secondary btn-sm pop-info" id="kembali"><i class="fas fa-angle-left"></i> Kembali</button> 
                                                <div class="float-right p-1">
                                                    <strong>{{ $transaksi->kode_transaksi }} - {!! statustransaksi($transaksi->status_transaksi) !!}</strong>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-body">
                                            @switch($s)
                                                @case('caribarang')
                                                    @include('sistem.transaksi.s.caribarang')
                                                    @include('sistem.transaksi.keranjang')
                                                    @break
                                                @case('bayar')
                                                    @include('sistem.transaksi.s.bayar')
                                                    @break
                                                @case('jumlahbarang')
                                                    @include('sistem.transaksi.s.jumlahbarang')
                                                    @include('sistem.transaksi.keranjang')
                                                    @break
                                                @default
                                                    @include('sistem.transaksi.keranjang')
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header p-2 bg-info">
                                        <strong class="h3">TOTAL PEMBAYARAN <span class="float-right"><i class="fas fa-money-bill-wave"></i> </span> </strong>
                                    </div>
                                    <div class="card-body p-0 text-right">
                                        <span class="float-left h4">Rp</span> <strong class="display-4 font-weight-bold">{{ norupiah($data['totalpembayaran']) }}</strong>
                                    </div>
                                </div>
                                {{-- button --}}
                                @include('sistem.transaksi.s.button')
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
        window.onload = maxWindow;
    
        function maxWindow() {
            window.moveTo(0, 0);
    
            if (document.all) {
                top.window.resizeTo(screen.availWidth, screen.availHeight);
            }
    
            else if (document.layers || document.getElementById) {
                if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth) {
                    top.window.outerHeight = screen.availHeight;
                    top.window.outerWidth = screen.availWidth;
                }
            }
        }
    </script> 
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
    <script>
        window.setTimeout("waktu()", 1000);
     
        function waktu() {
            var waktu = new Date();
            setTimeout("waktu()", 1000);
            document.getElementById("jam").innerHTML = waktu.getHours();
            document.getElementById("menit").innerHTML = waktu.getMinutes();
            document.getElementById("detik").innerHTML = waktu.getSeconds();
        }
    </script>
    @endsection
