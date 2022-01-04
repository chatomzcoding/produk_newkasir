@extends('layouts.admin')

@section('title')
    Data Laporan EOD
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Laporan EOD</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Laporan EOD</li>
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
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-list"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Laporan</span>
                  <span class="info-box-number">
                        {{ $statistik['total']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Laporan Bulan ini</span>
                  <span class="info-box-number">
                        {{ $statistik['totalbulanini']}}
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
                  <h3>Laporan EOD Bulan {{ bulan_indo($filter['bulan']).' '.$filter['tahun'] }}
                    <a href="{{ url('cetakdata?s=eodkasir&bulan='.$filter['bulan'].'&tahun'.$filter['tahun']) }}" class="btn btn-outline-info float-right btn-sm pop-info" title="Cetak Laporan EOD"><i class="fas fa-print"></i> Cetak</a>
                </h3>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-1">
                      <form action="{{ url('laporan') }}" method="get">
                        <div class="row">
                            <div class="form-group col-md-2">
                               <select name="bulan" id="" class="form-control" onchange="this.form.submit()">
                                   @for ($i = 1; $i <= 12; $i++)
                                   <option value="{{ $i }}" @if ($filter['bulan'] == $i)
                                       selected
                                   @endif>{{ bulan_indo($i) }}</option>
                                   @endfor
                               </select>
                            </div>
                            <div class="form-group col-md-2">
                               <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
                                   @for ($i = 2020; $i <= ambil_tahun(); $i++)
                                   <option value="{{ $i }}" @if ($filter['tahun'] == $i)
                                       selected
                                   @endif>{{ $i }}</option>
                                   @endfor
                               </select>
                            </div>
                        </div>
                    </form>
                  </section>
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%" rowspan="2">No</th>
                                <th rowspan="2">Tanggal Laporan</th>
                                <th colspan="4">Total</th>
                                <th colspan="2">Data</th>
                                <th rowspan="2">Sisa Uang</th>
                            </tr>
                            <tr>
                                <th>Transaksi</th>
                                <th>Item</th>
                                <th>Penjualan</th>
                                <th>Laba</th>
                                <th>Modal</th>
                                <th>Pengambilan</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @forelse ($laporan as $item)
                            <tr>
                                    <td class="text-center">{{ $loop->iteration}}</td>
                                    <td>{{ date_indo($item->tgl_laporan)}}</td>
                                    <td class="text-center">{{ $item->total_transaksi }}</td>
                                    <td class="text-center">{{ $item->total_item }}</td>
                                    <td class="text-right">{{ norupiah($item->total_penjualan) }}</td>
                                    <td class="text-right">{{ norupiah($item->laba) }}</td>
                                    <td class="text-right">{{ norupiah($item->modal) }}</td>
                                    <td class="text-right">{{ norupiah($item->pengambilan) }}</td>
                                    <td class="text-right">{{ norupiah(sisauangeod($item)) }}</td>
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

  
    @section('script')
        <script>
            $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
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

