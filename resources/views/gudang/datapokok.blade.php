<x-admin-layout title="Client" menu="client">
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Data Client</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Daftar Client</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </x-slot>
    <x-slot name="content">
        <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Data Pokok</h3>
                    <button class="float-right btn btn-success btn-sm" data-toggle="modal" data-target="#ubah"><i class="fas fa-pen"></i> EDIT</button>
                  </div>
                  <div class="card-body">
                      @include('sistem.notifikasi')
                      <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('img/client/'.$client->logo) }}" alt="logo" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Nama Toko</th>
                                            <td class="text-uppercase">: {{ $client->nama_toko }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pemilik</th>
                                            <td>: {{ $client->nama_pemilik }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>: {{ $client->alamat }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. Telepon</th>
                                            <td>: {{ $client->no_telp }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Penggunaan</th>
                                            <td>: {{ date_indo($client->tgl_bergabung) }}</td>
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
        </div>
        
        <div class="modal fade" id="ubah">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ route('client.update','test')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                <div class="modal-header">
                <h4 class="modal-title">Edit Data Client</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <section class="p-3">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" id="nama_pemilik"  value="{{ $client->nama_pemilik }}" class="form-control col-md-8" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Nama Toko</label>
                            <input type="text" name="nama_toko" id="nama_toko" value="{{ $client->nama_toko}}" class="form-control col-md-8" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">No Telp</label>
                            <input type="text" name="no_telp" id="no_telp" value="{{ $client->no_telp}}" class="form-control col-md-8" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Alamat</label>
                            <input type="text" name="alamat" id="alamat" value="{{ $client->alamat}}" class="form-control col-md-8" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control col-md-8">
                        </div>
                    </section>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </x-slot>
</x-admin-layout>
