@extends('layouts.admin')

@section('title')
    Data Distribusi
@endsection

@section('head')
<style>
    input[type=text] {
        border: 1px solid #bdbdbd;
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
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Distribusi - {{ $distribusi->kode }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('distribusi')}}">Daftar Distribusi</a></li>
            <li class="breadcrumb-item active">Detail Distribusi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    <a href="{{ url('distribusi') }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali Ke Daftar Distribusi"><i class="fas fa-angle-left"></i> Kembali</a>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <div class="alert alert-success">
                      <strong>Barang Ditemukan !</strong>
                  </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th>Kode Barang</th>
                                            <td>{{ $barang->kode_barang }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <td class="text-capitalize">{{ $barang->nama_barang }}</td>
                                        </tr>
                                        <tr>
                                            <th>Stok Barang</th>
                                            <td>{{ $barang->stok.' '.DbCikara::namaKategori($barang->satuan_id) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Harga Beli</th>
                                            <td>{{ rupiah($barang->harga_beli) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Harga Jual</th>
                                            <td>{{ rupiah($barang->harga_jual) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body pb-0">
                                   <div class="callout callout-info">
                                    <i class="fas fa-bullhorn text-info"></i> kenaikan/penurunan Harga barang, dapat disesuaikan
                                   </div>
                                   <form action="{{ url('distribusi/'.$distribusi->id) }}" method="post">
                                       @csrf
                                       @method('patch')
                                       <input type="hidden" name="s" value="tambahbarang">
                                       <input type="hidden" name="id" value="{{ $distribusi->id }}">
                                       <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                                       <input type="hidden" name="nama_barang" value="{{ $barang->nama_barang }}">
                                       <div class="form-group row mb-0">
                                            <label for="" class="col-md-4 p-2">Harga Beli</label>
                                            <input type="text" name="harga_beli" id="rupiah" class="form-control col-md-8" value="{{ $barang->harga_beli }}" required>
                                        </div>
                                       <div class="form-group row mb-0">
                                            <label for="" class="col-md-4 p-2">Harga Jual</label>
                                            <input type="text" name="harga_jual" id="rupiah1" class="form-control col-md-8" value="{{ $barang->harga_jual }}" required>
                                        </div>
                                       <div class="form-group row mb-0">
                                            <label for="" class="col-md-4 p-2">Jumlah Pembelian</label>
                                            <input type="number" name="jumlah" min="1" value="1" class="form-control col-md-8" required>
                                        </div>
                                        <div class="form-group text-right">
                                           <button type="submit" class="btn btn-primary btn-sm" id="tambahbarang"><i class="fas fa-plus"></i> TAMBAHKAN BARANG</button>
                                       </div>
                                   </form>
                                </div>
                            </div>
                        </div>
                    </div>                
              </div>
            </div>
          </div>
        </div>
    </div>
  
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
    
        </script>
            <script type="text/javascript">
                function myFunction(){
                    /* tombol shift R */
                    if(event.keyCode == 16) {
                        event.preventDefault()
                        $("#tambahbarang").click();
                    }
                }
            </script>
    @endsection

    @endsection

