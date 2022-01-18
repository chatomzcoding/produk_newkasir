@extends('layouts.admin')

@section('title')
    Data Cabang
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Cabang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Cabang</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-12">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store-alt"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Cabang</span>
                  <span class="info-box-number">
                        {{ count($cabang)}}
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
                    <a href="#" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Data Client Baru" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</a>
                    <a href="{{ url('cetakdata?s=cabang') }}" target="_blank" class="btn btn-outline-info btn-flat btn-sm float-right pop-info" title="Cetak Daftar Cabang"><i class="fas fa-print"></i> CETAK</a>
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
                                <th>Nama Cabang</th>
                                <th>Alamat</th>
                                <th>Pimpinan</th>
                                <th>No Telp</th>
                                <th>Tanggal Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @forelse ($cabang as $item)
                            <tr>
                                    <td class="text-center">{{ $loop->iteration}}</td>
                                    <td class="text-center">
                                        <form id="data-{{ $item->id }}" action="{{url('cabang/'.$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <button type="button" data-toggle="modal" data-nama_cabang="{{ $item->nama_cabang }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->telp }}" data-user_id="{{ $item->user_id }}" data-pimpinan="{{ $item->pimpinan }}" data-tgl_gabung="{{ $item->tgl_gabung }}" data-email="{{ $item->email }}"  data-id="{{ $item->id }}" data-target="#ubah" title="" class="dropdown-item" data-original-title="Edit Task">
                                                     EDIT <i class="fa fa-edit float-right pt-1 text-success"></i>
                                                    </button>
                                                  <div class="dropdown-divider"></div>
                                                  <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item">HAPUS<i class="fas fa-trash-alt float-right pt-1 text-danger"></i></button>
                                                </div>
                                            </div>
                                    </td>
                                    <td>{{ $item->nama_cabang}}</td>
                                    <td>{{ $item->alamat}}</td>
                                    <td>{{ $item->pimpinan}}</td>
                                    <td>{{ $item->telp}}</td>
                                    <td>{{ date_indo($item->tgl_gabung)}}</td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="8">tidak ada data</td>
                                </tr>
                            @endforelse
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="tambah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ url('/cabang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="level" value="cabang">
            <div class="modal-header">
            <h4 class="modal-title">Tambah Data Cabang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Cabang {!! ireq() !!}</label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Alamat {!! ireq() !!}</label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Telp {!! ireq() !!}</label>
                        <input type="text" name="telp" id="telp" value="{{ old('telp') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Pimpinan Cabang {!! ireq() !!}</label>
                        <input type="text" name="pimpinan" id="pimpinan" value="{{ old('pimpinan') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Bergabung {!! ireq() !!}</label>
                        <input type="date" name="tgl_gabung" id="tgl_gabung" value="{{ old('tgl_gabung') }}" class="form-control col-md-8" required>
                    </div>
                    <hr>
                    <h4>Akses Login Pimpinan Cabang</h4>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Email Login {!! ireq() !!}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Password {!! ireq() !!}</label>
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
    <!-- /.modal -->

    {{-- modal edit --}}
    <div class="modal fade" id="ubah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('cabang.update','test')}}" method="post">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit Data Cabang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="id" id="id">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Cabang {!! ireq() !!}</label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Telp {!! ireq() !!}</label>
                        <input type="text" name="telp" id="telp" value="{{ old('telp') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Pimpinan Cabang {!! ireq() !!}</label>
                        <input type="text" name="pimpinan" id="pimpinan" value="{{ old('pimpinan') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Bergabung {!! ireq() !!}</label>
                        <input type="date" name="tgl_gabung" id="tgl_gabung" value="{{ old('tgl_gabung') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Alamat {!! ireq() !!}</label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control col-md-8" required>
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
                var nama_cabang = button.data('nama_cabang')
                var alamat = button.data('alamat')
                var telp = button.data('telp')
                var tgl_gabung = button.data('tgl_gabung')
                var pimpinan = button.data('pimpinan')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #nama_cabang').val(nama_cabang);
                modal.find('.modal-body #alamat').val(alamat);
                modal.find('.modal-body #telp').val(telp);
                modal.find('.modal-body #tgl_gabung').val(tgl_gabung);
                modal.find('.modal-body #pimpinan').val(pimpinan);
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

