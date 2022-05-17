@extends('layouts.kasir')

@section('title')
{{ $transaksi->kode_transaksi }}
@endsection

@section('head')
<style>
    input[type=text] {
        border: 2px solid #bdbdbd;
        font-family: 'Roboto', Arial, Sans-serif;
        font-size: 15px;
        font-weight: 400;
        padding: .5em .75em;
        width: 100%;
    }
    input[type=text]:focus {
        border: 2px solid #757575;
        outline: none;
    }
    .autocomplete-suggestions {
        border: 1px solid #999;
        background: #FFF;
        overflow: auto;
    }
    .autocomplete-suggestion {
        padding: 2px 5px;
        white-space: nowrap;
        overflow: hidden;
    }
    .autocomplete-selected {
        background: #F0F0F0;
    }
    .autocomplete-suggestions strong {
        font-weight: normal;
        color: #3399FF;
    }
    .autocomplete-group {
        padding: 2px 5px;
    }
    .autocomplete-group strong {
        display: block;
        border-bottom: 1px solid #000;
    }
</style>
@endsection

@section('header')
    <div class="row">
        <div class="col-12">
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
            <div class="row">
                <div class="col-md-9">
                    @include('sistem.notifikasi')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ url('transaksi') }}" method="get">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm pop-info" id="kembali"><i class="fas fa-long-arrow-alt-left"></i></button> 
                                        <div class="float-right p-1">
                                            <strong>{{ $transaksi->kode_transaksi }} - {!! statustransaksi($transaksi->status_transaksi) !!}</strong>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body pt-1">
                                    @if ($transaksi->status_transaksi == 'retur')
                                        @include('sistem.transaksi.s.retur')
                                    @endif
                                    @switch($s)
                                        @case('caribarang')
                                            @include('sistem.transaksi.s.caribarang')
                                            @include('sistem.transaksi.keranjang')
                                            @break
                                        @case('bayar')
                                            @include('sistem.transaksi.s.bayar')
                                            @break
                                        @case('tambahtransaksi')
                                            @include('sistem.transaksi.s.tambahtransaksi')
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
                        <div class="card">
                            <div class="card-body p-2">
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
            /* tombol Page Up */
            if(event.keyCode == 33) {
                event.preventDefault()
                $("#tambahtransaksi").click();
            }
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
            /* tombol End */
            if(event.keyCode == 35) {
                event.preventDefault()
                $("#batalretur").click();
            }
              /* tombol F1 */
      if(event.keyCode == 112) {
          event.preventDefault()
          $("#2000").click();
      }
      /* tombol F2 */
      if(event.keyCode == 113) {
          event.preventDefault()
          $("#5000").click();
      }
      /* tombol F3 */
      if(event.keyCode == 114) {
          event.preventDefault()
          $("#10000").click();
      }
      /* tombol F4 */
      if(event.keyCode == 115) {
          event.preventDefault()
          $("#20000").click();
      }
      /* tombol F5 */
      if(event.keyCode == 116) {
          event.preventDefault()
          $("#50000").click();
      }
      /* tombol F6 */
      if(event.keyCode == 117) {
          event.preventDefault()
          $("#100000").click();
      }
      /* tombol F7 */
      if(event.keyCode == 118) {
          event.preventDefault()
          $("#150000").click();
      }
      /* tombol F8 */
      if(event.keyCode == 119) {
          event.preventDefault()
          $("#200000").click();
      }
      /* tombol F9 */
      if(event.keyCode == 120) {
          event.preventDefault()
          $("#bayarpas").click();
      }
      /* tombol shift R */
      if(event.keyCode == 16) {
          event.preventDefault()
          $("#bayarsekarang").click();
      }
      if(event.keyCode == 46) {
          event.preventDefault()
          $("#klikdelete").click();
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
