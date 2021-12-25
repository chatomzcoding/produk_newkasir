@extends('layouts.admin')

@section('title')
    Data Transaksi
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Transaksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Transaksi</li>
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
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Transaksi</span>
                  <span class="info-box-number">
                        {{ count($transaksi)}}
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
                <form action="{{ url('transaksi') }}" method="post">
                    @csrf
                    <input type="hidden" name="sesi" value="tambah">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="kode_transaksi" value="{{ DbCikara::kodeTransaksi($user->id) }}">
                    <input type="hidden" name="status_transaksi" value="proses">
                    <input type="hidden" name="uang_pembeli" value="0">
                    <button type="submit" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Transaksi"><i class="fas fa-plus"></i> Tambah</button>
                </form>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  {{-- <section class="mb-3">
                      <form action="{{ url('listdata') }}" method="get">
                        <div class="row">
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
                        </div>
                    </form>
                  </section> --}}
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Aksi</th>
                                <th>Kode Transaksi</th>
                                <th>Tipe Pembayaran</th>
                                <th>Tipe Orderan</th>
                                <th>Uang Pembeli</th>
                                <th>Keranjang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @forelse ($transaksi as $item)
                            <tr>
                                    <td class="text-center">{{ $loop->iteration}}</td>
                                    <td class="text-center">
                                        <form id="data-{{ $item->id }}" action="{{url('kategori/'.$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="{{ url('transaksi/'.Crypt::encryptString($item->id)) }}" class="dropdown-item text-primary"><i class="fas fa-file"></i>  DETAIL</a>
                                                    <button type="button" data-toggle="modal" data-nama="{{ $item->nama }}"  data-keterangan="{{ $item->keterangan }}"  data-id="{{ $item->id }}" data-target="#ubah" title="" class="dropdown-item text-success" data-original-title="Edit Task">
                                                    <i class="fa fa-edit"></i> EDIT
                                                    </button>
                                                  <div class="dropdown-divider"></div>
                                                  <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item text-danger"><i class="fas fa-trash-alt"></i> HAPUS</button>
                                                </div>
                                            </div>
                                    </td>
                                    <td>{{ $item->kode_transaksi}}</td>
                                    <td>{{ $item->tipe_pembayaran}}</td>
                                    <td>{{ $item->tipe_orderan}}</td>
                                    <td>{{ $item->uang_pembeli}}</td>
                                    <td>{{ $item->keranjang}}</td>
                                    <td>{{ $item->status}}</td>
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
            <form action="{{ url('/kategori')}}" method="post">
                @csrf
                <input type="hidden" name="label" value="kategori">
            <div class="modal-header">
            <h4 class="modal-title">Tambah Data Kategori Barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Kategori</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Keterangan (opsional)</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control col-md-8"></textarea>
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
            <form action="{{ route('listdata.update','test')}}" method="post">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit Data List</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="id" id="id">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control col-md-8" required>
                            @foreach (list_kategori() as $item)
                                <option value="{{ $item }}">{{ strtoupper($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama List Data</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Keterangan (opsional)</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control col-md-8"></textarea>
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
                var nama = button.data('nama')
                var kategori = button.data('kategori')
                var keterangan = button.data('keterangan')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #nama').val(nama);
                modal.find('.modal-body #kategori').val(kategori);
                modal.find('.modal-body #keterangan').val(keterangan);
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

