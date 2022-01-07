@extends('layouts.admin')

@section('title')
    Data Distribusi
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Distribusi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Distribusi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-truck"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Distribusi</span>
                  <span class="info-box-number">
                        {{ $statistik['total']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-truck"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Distribusi Bulan ini</span>
                  <span class="info-box-number">
                        {{ $statistik['totalbulanini']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sync"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Distribusi Dalam Proses</span>
                  <span class="info-box-number">
                        {{ $statistik['totalproses']}}
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
                    <a href="#" class="btn btn-outline-primary btn-sm pop-info" title="Tambah Data Distribusi Baru" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</a>
                    <a href="{{ url('distribusi') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-sync"></i> Bersihkan Filter</a>
                    <div class="float-right">
                        @if (!$filter['page'])
                        <span class="badge badge-info p-2">Total Data pencarian : {{ count($datatabel) }}</span>
                    @endif
                    <a href="{{ url('cetakdata?s=distribusi&tanggal='.$filter['data']['tanggal']) }}" class="btn btn-outline-info btn-sm pop-info" target="_blank" title="Cetak daftar Distribusi"><i class="fas fa-print"></i> CETAK</a>
                    </div>

              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-3">
                      <form action="{{ url('distribusi') }}" method="get">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <select name="waktu" id="" class="form-control" onchange="this.form.submit()">
                                        <option value="semua" @if ($filter['waktu'] == 'semua')
                                        selected
                                    @endif>SEMUA</option>
                                        <option value="harian" @if ($filter['waktu'] == 'harian')
                                            selected
                                        @endif>HARIAN</option>
                                        <option value="bulanan" @if ($filter['waktu'] == 'bulanan')
                                        selected
                                    @endif>BULANAN</option>
                                        <option value="tahunan" @if ($filter['waktu'] == 'tahunan')
                                        selected
                                    @endif>TAHUNAN</option>
                                    </select>
                                </div>
                                @switch($filter['waktu'])
                                    @case('harian')
                                        @include('sistem.filter.harian')
                                        @break
                                    @case('bulanan')
                                        @include('sistem.filter.bulanan')
                                        @break
                                    @case('tahunan')
                                        @include('sistem.filter.tahunan')
                                        @break
                                    @default
                                        
                                @endswitch
                            </div>
                        </form>
                  </section>
                  <div class="table-responsive">
                    <table id="{{ cekdatatable($filter['page']) }}" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Aksi</th>
                                <th>Kode</th>
                                <th>No Faktur</th>
                                <th>Tanggal Faktur</th>
                                <th>Potongan</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @forelse ($datatabel as $item)
                            <tr>
                                    <td class="text-center">{{ $loop->iteration}}</td>
                                    <td class="text-center">
                                        <form id="data-{{ $item->id }}" action="{{url('distribusi/'.$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="{{ url('distribusi/'.Crypt::encryptString($item->id)) }}" class="dropdown-item">DETAIL <i class="fas fa-file text-primary float-right"></i></a>
                                                    <button type="button" data-toggle="modal" data-kode_distribusi="{{ $item->kode_distribusi }}" data-no_faktur="{{ $item->no_faktur }}"  data-tgl_faktur="{{ $item->tgl_faktur }}" data-tgl_tempo="{{ $item->tgl_tempo }}" data-pembayaran="{{ $item->pembayaran }}" data-potongan="{{ $item->potongan }}" data-supplier_id="{{ $item->supplier_id }}"  data-id="{{ $item->id }}" data-target="#ubah" title="" class="dropdown-item" data-original-title="Edit Task">
                                                     EDIT <i class="fa fa-edit float-right text-success"></i>
                                                    </button>
                                                  <div class="dropdown-divider"></div>
                                                  <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item"> HAPUS <i class="fas fa-trash-alt float-right text-danger"></i></button>
                                                </div>
                                            </div>
                                    </td>
                                    <td>{{ $item->kode_distribusi}}</td>
                                    <td>{{ $item->no_faktur}}</td>
                                    <td>{{ date_indo($item->tgl_faktur)}}</td>
                                    <td class="text-right">{{ norupiah($item->potongan)}}</td>
                                    <td class="text-center text-uppercase">{{ $item->pembayaran}}</td>
                                    <td class="text-center">{!! showstatus($item->status_stok)!!}</td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="8">tidak ada data</td>
                                </tr>
                            @endforelse
                    </table>
                    @includeWhen($filter['page'], 'sistem.pagination',['link' => $filter['link']])

                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="tambah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ url('/distribusi')}}" method="post">
                @csrf
                <input type="hidden" name="status_stok" value="proses">
                <input type="hidden" name="s" value="tambahdistribusi">
                <input type="hidden" name="cabang_id" value="{{ $akses->cabang_id }}">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Distribusi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Kode Distribusi</label>
                        <input type="text" name="kode_distribusi" id="kode_distribusi" value="{{ DbCikara::kodeDistribusi($akses->user_id) }}" class="form-control col-md-8" readonly>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Faktur/Bukti Pembayaran</label>
                        <input type="text" name="no_faktur" id="no_faktur" value="{{ old('no_faktur') }}" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Faktur/Pembelian</label>
                        <input type="date" name="tgl_faktur" id="tgl_faktur" value="{{ old('tgl_faktur') }}" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tgl_tempo" id="tgl_tempo" value="{{ old('tgl_tempo') }}" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Supplier</label>
                        <div class="col-md-8 p-0">
                            <select name="supplier_id" id="supplier_id" class="form-control listdata" data-width="100%" required>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Pembayaran</label>
                        <select name="pembayaran" id="pembayaran" class="form-control col-md-8" required>
                            <option value="tunai">TUNAI</option>
                            <option value="kredit">KREDIT</option>
                            <option value="konsinyasi">KONSINYASI</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Potongan Harga</label>
                        <input type="text" name="potongan" id="potongan" value="{{ old('potongan') }}" class="form-control col-md-8">
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
            <form action="{{ route('distribusi.update','test')}}" method="post">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit Data Distribusi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="s" value="editdistribusi">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Kode Distribusi <strong class="text-danger">*</strong></label>
                        <input type="text" name="kode_distribusi" id="kode_distribusi" class="form-control col-md-8" readonly>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">No Faktur/Bukti Pembayaran</label>
                        <input type="text" name="no_faktur" id="no_faktur" value="{{ old('no_faktur') }}" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Faktur/Pembelian <strong class="text-danger">*</strong></label>
                        <input type="date" name="tgl_faktur" id="tgl_faktur" value="{{ old('tgl_faktur') }}" class="form-control col-md-8" required>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tgl_tempo" id="tgl_tempo" value="{{ old('tgl_tempo') }}" class="form-control col-md-8">
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Supplier <strong class="text-danger">*</strong></label>
                        <div class="col-md-8 p-0">
                            <select name="supplier_id" id="supplier_id" class="form-control listdata" data-width="100%" required>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Pembayaran <strong class="text-danger">*</strong></label>
                        <select name="pembayaran" id="pembayaran" class="form-control col-md-8" required>
                            <option value="tunai">TUNAI</option>
                            <option value="kredit">KREDIT</option>
                            <option value="konsinyasi">KONSINYASI</option>

                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-4">Potongan Harga</label>
                        <input type="text" name="potongan" id="potongan" value="{{ old('potongan') }}" class="form-control col-md-8">
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
                var kode_distribusi = button.data('kode_distribusi')
                var tgl_faktur = button.data('tgl_faktur')
                var no_faktur = button.data('no_faktur')
                var tgl_tempo = button.data('tgl_tempo')
                var pembayaran = button.data('pembayaran')
                var potongan = button.data('potongan')
                var supplier_id = button.data('supplier_id')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #kode_distribusi').val(kode_distribusi);
                modal.find('.modal-body #tgl_faktur').val(tgl_faktur);
                modal.find('.modal-body #no_faktur').val(no_faktur);
                modal.find('.modal-body #tgl_tempo').val(tgl_tempo);
                modal.find('.modal-body #pembayaran').val(pembayaran);
                modal.find('.modal-body #potongan').val(potongan);
                modal.find('.modal-body #supplier_id').val(supplier_id);
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

