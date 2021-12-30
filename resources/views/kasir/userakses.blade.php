@extends('layouts.admin')

@section('title')
    Data Akses User
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Akses User</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Akses User</li>
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
                <h3 class="card-title">Daftar Akses User</h3>
                    {{-- <a href="#" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Data List Baru" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</a> --}}
              </div>
              <div class="card-body">
                    @include('sistem.notifikasi')
                    @if ($userakses->app_key == NULL || $userakses->app_port == NULL)
                        <div class="alert alert-warning">
                            <p><i class="fas fa-exclamation-circle"></i> Peringatan!</p>
                            Silahkan setting <strong>app key</strong> dan <strong>app port</strong> untuk dapat cetak transaksi. App Key dan App Port dapat dilihat pada aplikasi recta host.
                        </div>
                    @else
                    <h5>Perangkat kasir sudah terhubung</h5>
                    @endif
                    <form action="{{ url('/userakses/'.$userakses->id) }}" method="post">
                        @csrf   
                        @method('patch')
                        <input type="hidden" name="id" value="{{ $userakses->id }}">
                        <div class="form-group row">
                            <label for="" class="col-md-4">App Key</label>
                            <input type="text" name="app_key" pattern="[0-9]+" class="form-control col-md-8" value="{{ $userakses->app_key }}" placeholder="masukkan app key disini" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">App Port</label>
                            <input type="text" name="app_port"  pattern="[0-9]+" class="form-control col-md-8" value="{{ $userakses->app_port }}" placeholder="masukkan app port disini" required>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
    </div>

    @endsection

