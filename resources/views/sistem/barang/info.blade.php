@extends('layouts.admin')

@section('title')
    Informasi Barang
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Informasi Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('barang')}}">Daftar Barang</a></li>
            <li class="breadcrumb-item active">Info Barang</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('container')
  
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    {{-- <a href="{{ url('barang/'.Crypt::encryptString($barang->id)) }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali ke detail Barang"><i class="fas fa-angle-left"></i> Kembali</a> --}}
              </div>
              <div class="card-body">
                  <div class="">

                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>


    @endsection

