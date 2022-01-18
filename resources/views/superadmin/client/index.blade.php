@extends('layouts.admin')

@section('title')
    Data Client
@endsection

@section('header')
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
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-tie"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Client</span>
                  <span class="info-box-number">
                        {{ count($client)}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
  
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
  
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-store"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Jenis Toko</span>
                  <span class="info-box-number">
                    {{ DbCikara::countdata('list_data',['kategori','jenis toko']) }}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    @if (count($user) == count($client))
                        <a href="#" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Data Client Baru" data-toggle="modal" data-target="#tambahuser"><i class="fas fa-plus"></i> Tambah</a>
                    @else
                        <a href="#" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Data Client Baru" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</a>
                    @endif
                    {{-- <a href="#" data-toggle="modal" data-target="#cetakdokumen" class="btn btn-outline-info btn-flat btn-sm float-right pop-info" title="Cetak Daftar Vaksinasi"><i class="fas fa-print"></i> CETAK</a> --}}
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-3">
                      <form action="{{ url('listdata') }}" method="get">
                        {{-- <div class="row">
                            <div class="form-group col-md-2">
                                <select name="kategori" id="" class="form-control form-control-sm" onchange="this.form.submit();">
                                    <option value="semua">Semua</option>
                                    @foreach (list_kategori() as $item)
                                        <option value="{{ $item}}" @if ($kategori == $item)
                                            selected
                                        @endif>{{ strtoupper($item)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                    </form>
                  </section>
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Aksi</th>
                                <th>Logo</th>
                                <th>Nama Pemilik</th>
                                <th>Nama Toko</th>
                                <th>Jenis retail</th>
                                <th>No Telp</th>
                                <th>Alamat</th>
                                <th>Tanggal Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @forelse ($client as $item)
                            <tr>
                                    <td class="text-center">{{ $loop->iteration}}</td>
                                    <td class="text-center">
                                        <form id="data-{{ $item->id }}" action="{{url('client/'.$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                  {{-- <a class="dropdown-item text-primary" href="{{ url('/penduduk/'.Crypt::encryptString($item->penduduk_id))}}"><i class="fas fa-list"></i> Detail Penduduk</a> --}}
                                                    <button type="button" data-toggle="modal" data-nama_pemilik="{{ $item->nama_pemilik }}" data-nama_toko="{{ $item->nama_toko }}" data-no_telp="{{ $item->no_telp }}" data-alamat="{{ $item->alamat }}" data-tgl_bergabung="{{ $item->tgl_bergabung }}" data-jenis_retail="{{ $item->jenis_retail }}" data-detail="{{ $item->detail }}" data-user_id="{{ $item->user_id }}"  data-id="{{ $item->id }}" data-target="#ubah" title="" class="dropdown-item" data-original-title="Edit Task">
                                                     EDIT <i class="fa fa-edit float-right pt-1 text-success"></i>
                                                    </button>
                                                  <div class="dropdown-divider"></div>
                                                  <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item"> HAPUS <i class="fas fa-trash-alt float-right pt-1 text-danger"></i></button>
                                                </div>
                                            </div>
                                    </td>
                                    <td> <img src="{{ asset('img/client/'.$item->logo) }}" alt="logo" width="90px"></td>
                                    <td>{{ $item->nama_pemilik}}</td>
                                    <td>{{ $item->nama_toko}}</td>
                                    <td>{{ $item->jenis_retail}}</td>
                                    <td>{{ $item->no_telp}}</td>
                                    <td>{{ $item->alamat}}</td>
                                    <td>{{ date_indo($item->tgl_bergabung)}}</td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="9">tidak ada data</td>
                                </tr>
                            @endforelse
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="tambahuser">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ url('/user')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="level" value="client">
            <div class="modal-header">
            <h4 class="modal-title">Tambah User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                    <div class="alert alert-info">
                        <strong>BELUM ADA AKUN USER</strong> <br>
                        Sebelum menambahkan akun client, terlebih dahulu menambahkan akun user, setelah itu akun client dapat dibuat !
                    </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Nama User</label>
                        <input type="text" name="name" id="name" class="form-control col-md-8" required>
                   </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Email</label>
                        <input type="email" name="email" id="email" class="form-control col-md-8" required>
                   </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Password</label>
                        <input type="password" name="password" id="password" class="form-control col-md-8" required>
                   </div>
                </section>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> SIMPAN</button>
            </div>
        </form>
        </div>
        </div>
    </div>

    <div class="modal fade" id="tambah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ url('/client')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="modal-header">
            <h4 class="modal-title">Tambah Data Client</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Pemilik {!! ireq() !!}</label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Toko {!! ireq() !!}</label>
                        <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Telp {!! ireq() !!}</label>
                        <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" pattern="[0-9]{+}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Alamat {!! ireq() !!}</label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Bergabung {!! ireq() !!}</label>
                        <input type="date" name="tgl_bergabung" id="tgl_bergabung" value="{{ old('tgl_bergabung') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Logo {!! ireq() !!}</label>
                        <input type="file" name="logo" id="logo" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Jenis Toko {!! ireq() !!}</label>
                        <select name="jenis_retail" id="jenis_retail" class="form-control col-md-8" required>
                            @foreach (DbCikara::showtable('list_data',['kategori','jenis toko']) as $item)
                                <option value="{{ $item->nama }}">{{ strtoupper($item->nama) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama User {!! ireq() !!}</label>
                        <select name="user_id" id="user_id" class="form-control col-md-8" onchange="location = this.value;" required>
                            @foreach ($user as $item)
                                @if (DbCikara::countData('client',['user_id',$item->id]) == 0)
                                    <option value="{{ $item->id }}">{{ strtoupper($item->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Detail (opsional)</label>
                        <textarea name="detail" id="detail" cols="30" rows="4" class="form-control col-md-8"></textarea>
                    </div>
                </section>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> SIMPAN</button>
            </div>
        </form>
        </div>
        </div>
    </div>
    <!-- /.modal -->

    {{-- modal edit --}}
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
                <input type="hidden" name="id" id="id">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Telp</label>
                        <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Alamat</label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Bergabung</label>
                        <input type="date" name="tgl_bergabung" id="tgl_bergabung" value="{{ old('tgl_bergabung') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Jenis Toko</label>
                        <select name="jenis_retail" id="jenis_retail" class="form-control col-md-8" required>
                            @foreach (DbCikara::showtable('list_data',['kategori','jenis toko']) as $item)
                                <option value="{{ $item->nama }}">{{ strtoupper($item->nama) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama User</label>
                        <select name="user_id" id="user_id" class="form-control col-md-8" required>
                            @foreach ($user as $item)
                                <option value="{{ $item->id }}">{{ strtoupper($item->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Detail (opsional)</label>
                        <textarea name="detail" id="detail" cols="30" rows="4" class="form-control col-md-8"></textarea>
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
    <!-- /.modal -->

    @section('script')
        
        <script>
            $('#ubah').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var nama_pemilik = button.data('nama_pemilik')
                var nama_toko = button.data('nama_toko')
                var jenis_retail = button.data('jenis_retail')
                var no_telp = button.data('no_telp')
                var alamat = button.data('alamat')
                var tgl_bergabung = button.data('tgl_bergabung')
                var detail = button.data('detail')
                var user_id = button.data('user_id')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #nama_pemilik').val(nama_pemilik);
                modal.find('.modal-body #nama_toko').val(nama_toko);
                modal.find('.modal-body #jenis_retail').val(jenis_retail);
                modal.find('.modal-body #no_telp').val(no_telp);
                modal.find('.modal-body #alamat').val(alamat);
                modal.find('.modal-body #tgl_bergabung').val(tgl_bergabung);
                modal.find('.modal-body #detail').val(detail);
                modal.find('.modal-body #user_id').val(user_id);
                modal.find('.modal-body #id').val(id);
            })
        </script>
        <script>
            $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy","excel"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            });
        </script>
    @endsection

    @endsection

