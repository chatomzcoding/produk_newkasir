@extends('layouts.admin')

@section('title')
    Data Barang
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Barang</li>
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
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-box-open"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Barang</span>
                      <span class="info-box-number">
                        {{ count($barang) }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
      
                <!-- fix for small devices only -->
      
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-times"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Karyawan Gudang</span>
                      <span class="info-box-number">
                        1
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-times"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Karyawan Kasir</span>
                      <span class="info-box-number">
                        1
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
              </div>
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    <a href="{{ url('barang/create') }}" class="btn btn-outline-primary btn-sm pop-info" title="Tambah Barang Baru"><i class="fas fa-plus"></i> Tambah</a>
                    <div class="float-right">
                      <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm ">Option</button>
                        <button type="button" class="btn btn-info btn-sm  dropdown-toggle dropdown-icon" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                          <div class="dropdown-menu" role="menu">
                            <div class="dropdown-divider"></div>
                            <form action="{{url('/barang/semua')}}" method="post">
                              @csrf
                              @method('delete')
                                <button class="dropdown-item text-danger" type="submit"><i class="fas fa-trash-alt"></i> Hapus Semua</button>
                              </form>
                          </div>
                      </div>
                    <a href="{{ url('cetakdata?s=user') }}" class="btn btn-outline-info btn-sm pop-info" target="_blank" title="Cetak daftar User"><i class="fas fa-print"></i> CETAK</a>
                    </div>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-3">
                    <form action="{{ url('barang') }}" method="get">
                      @csrf
                      <div class="row">
                          <div class="form-group col-md-2">
                              <select name="kategori" id="" class="form-control form-control-sm" onchange="this.form.submit();">
                                  <option value="semua">-- Semua Kategori</option>
                                  @foreach ($kategori as $item)
                                      <option value="{{ $item->id}}" @if ($filter['kategori'] == $item->id)
                                          selected
                                      @endif>{{ strtoupper($item->nama)}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </form>
                </section>
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Aksi</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <form id="data-{{ $item->id }}" action="{{url('/barang',$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                              <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                              </button>
                                              <div class="dropdown-menu" role="menu">
                                                  <button type="button" data-toggle="modal" data-name ="{{ $item->name }}" data-email ="{{ $item->email }}" data-id="{{ $item->id }}" data-target="#ubah" title="" class="dropdown-item text-success" data-original-title="Edit Task">
                                                  <i class="fa fa-edit"></i> Edit User
                                                  </button>
                                                <div class="dropdown-divider"></div>
                                                <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item text-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
                                              </div>
                                          </div>
                                    </td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ rupiah($item->harga_beli) }} </td>
                                    <td>{{ rupiah($item->harga_jual) }} </td>
                                    <td>{{ DbCikara::namaKategori($item->kategori_id) }}</td>
                                    <td class="text-center">{{ $item->stok }} </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
 {{-- modal --}}
    {{-- modal tambah --}}
    <div class="modal fade" id="tambah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ url('/user')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="modal-header">
            <h4 class="modal-title">Tambah User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                   <div class="form-group row">
                        <label for="" class="col-md-4">Nama Karywan</label>
                        <input type="text" name="name" id="name" class="form-control col-md-8" required>
                   </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Status Karyawan</label>
                        <select name="level" id="level" class="form-control col-md-8" required>
                            <option value="">-- PILIH STATUS --</option>
                            <option value="gudang">GUDANG</option>
                            <option value="kasir">KASIR</option>
                        </select>
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
    <!-- /.modal -->

    {{-- modal edit --}}
    <div class="modal fade" id="ubah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('user.update','test')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="id" id="id">
                <section class="p-3">
                   <div class="form-group row">
                        <label for="" class="col-md-4">Email</label>
                        <input type="email" name="email" id="email" class="form-control col-md-8" required>
                   </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Password (isi jika ingin diubah)</label>
                        <input type="password" name="password" id="password" class="form-control col-md-8">
                   </div>
                </section>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-pen"></i> SIMPAN PERUBAHAN</button>
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
                var email = button.data('email')
                var password = button.data('password')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #email').val(email);
                modal.find('.modal-body #password').val(password);
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
