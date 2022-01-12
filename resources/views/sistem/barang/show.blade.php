@extends('layouts.admin')

@section('title')
    Data Barang
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Detail Barang {{ ucwords($barang->nama_barang) }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('barang')}}">Daftar Barang</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('container')
    
  
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- statistik -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-boxes"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Stok Barang</span>
                      <span class="info-box-number">
                        {{ $statistik['stokbarang'].' '.DbCikara::namaKategori($barang->satuan_id) }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cart-arrow-down"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Terjual</span>
                      <span class="info-box-number">
                        {{ $statistik['totalterjual'] }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Omzet Dalam Barang</span>
                      <span class="info-box-number">
                        {{ rupiah(subtotal($barang->harga_jual,$barang->stok)) }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </div>
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                <a href="{{ url('barang') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-angle-left"></i> Kembali</a>
                <a href="{{ url('barang/'.Crypt::encryptString($barang->id).'/edit') }}" class="btn btn-outline-success btn-sm"><i class="fas fa-pen"></i> Edit</a>
                    <div class="float-right">
                    <a href="{{ url('cetakdata?s=detailbarang&id='.Crypt::encryptString($barang->id)) }}" class="btn btn-outline-info btn-sm pop-info" target="_blank" title="Cetak barang"><i class="fas fa-print"></i> CETAK</a>
                    </div>
              </div>
              <div class="card-body">
                    @include('sistem.notifikasi')
                    @if ($barang->stok <= 0)
                        <div class="alert alert-warning">
                            <strong>Segera Tambah Stok !</strong>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4">
                            @if (is_null($barang->gambar))
                                <img src="{{ asset('img/produk.jpg') }}" alt="" class="img-fluid">
                            @else
                                <img src="{{ asset('img/barang/'.$barang->gambar) }}" alt="" class="img-fluid">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Kode Barang</th>
                                        <td>: {{ $barang->kode_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kode Barcode</th>
                                        <td>: {{ $barang->kode_barcode }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori Barang</th>
                                        <td class="text-uppercase">: {{ DbCikara::namaKategori($barang->kategori_id) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Beli</th>
                                        <td>: {{ rupiah($barang->harga_beli) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Jual</th>
                                        <td>: {{ rupiah($barang->harga_jual) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Barang</th>
                                        <td class="text-uppercase">: {{ $barang->status_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merk Barang</th>
                                        <td>: {{ $barang->merk }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
              </div>
            </div>
          </div>
        </div>
    </div>

       
    @endsection

