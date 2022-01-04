@extends('layouts.admin')

@section('title')
    Data Migrasi Database
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Migrasi Database</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Migrasi Database</li>
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
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tabel</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-capitalize">
                                @for ($i = 0; $i < count($migrasi); $i++)
                                    @php
                                        $no = 1 + $i; 
                                    @endphp
                                    <tr>
                                        <th>{{ $no }}</th>
                                        <th>{{ $migrasi[$i] }}</th>
                                        <th><a href="{{ url('page/'.$migrasi[$i]) }}" class="btn btn-primary btn-sm">LIHAT DATA</a></th>
                                    </tr>
                                @endfor
                            </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>


    @endsection

