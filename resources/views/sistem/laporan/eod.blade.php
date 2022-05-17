<x-admin-layout title="Laporan EOD" menu="laporaneod">
  <x-slot name="header">
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
  </x-slot>
  <x-slot name="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-list"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text">Total Laporan</span>
                  <span class="info-box-number">
                        {{ $data['statistik']['total']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text">Total Transaksi</span>
                  <span class="info-box-number">
                        {{ norupiah($data['statistik']['totaltransaksi'])}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text">Total Item Terjual</span>
                  <span class="info-box-number">
                        {{ norupiah($data['statistik']['totalitem'])}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text">Total Penjualan</span>
                  <span class="info-box-number">
                        {{ rupiah($data['statistik']['totalpenjualan'])}}
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
                  <h3>Laporan EOD Bulan
                    {{-- <a href="{{ url('cetakdata?s=eodkasir&bulan='.$filter['bulan'].'&tahun'.$filter['tahun']) }}" class="btn btn-outline-info float-right btn-sm pop-info" title="Cetak Laporan EOD"><i class="fas fa-print"></i> Cetak</a> --}}
                </h3>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-1">
                      <form action="{{ url('datalaporan/eod') }}" method="get">
                        <div class="row">
                          <div class="col-md-2">
                            <a href="{{ url('datalaporan/eod') }}" class="btn btn-outline-secondary btn-block"><i class="fas fa-filter"></i> BERSIHKAN FILTER</a>
                          </div>
                            <div class="form-group col-md-3">
                               <select name="user_id" id="" class="form-control listdata" data-width="100%" onchange="this.form.submit()">
                                <option value="semua">-- SEMUA KASIR --</option>
                                    @foreach ($data['user'] as $item)
                                        <option value="{{ $item->id }}" @if ($data['user_id'] == $item->id)
                                            selected
                                        @endif>{{ strtoupper($item->name) }}</option>
                                    @endforeach
                               </select>
                            </div>
                            <div class="form-group col-md-2">
                               <select name="sesi" id="" class="form-control" onchange="this.form.submit()">
                                  <option value="semua">-- SEMUA LAPORAN --</option>
                                  <option value="harian" @if ($data['sesi'] == 'harian')
                                      selected
                                  @endif>HARIAN</option>
                                  <option value="bulanan" @if ($data['sesi'] == 'bulanan')
                                      selected
                                  @endif>BULANAN</option>
                                  <option value="tahunan" @if ($data['sesi'] == 'tahunan')
                                      selected
                                  @endif>TAHUNAN</option>
                               </select>
                            </div>
                            @if ($data['sesi'] == 'harian')
                            <div class="form group col-md-2">
                              <input type="date" name="tanggal" value="{{ $data['waktu']['tanggal'] }}" class="form-control" onchange="this.form.submit()">
                            </div>
                            @endif
                            @if ($data['sesi'] == 'bulanan')
                            <div class="col-md-2">
                              <select name="bulan" id="" class="form-control" onchange="this.form.submit()">
                                  @for ($i = 1; $i <= 12; $i++)
                                      <option value="{{ $i }}" @if ($data['waktu']['bulan'] == $i)
                                          selected
                                      @endif>{{ bulan_indo($i) }}</option>
                                  @endfor
                              </select>
                            </div>
                            <div class="col-md-2">
                                <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
                                    @for ($i = 2020; $i <= ambil_tahun(); $i++)
                                        <option value="{{ $i }}" @if ($data['waktu']['tahun'] == $i)
                                            selected
                                        @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @endif
                            @if ($data['sesi'] == 'tahunan')
                            <div class="col-md-2">
                                <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
                                    @for ($i = 2020; $i <= ambil_tahun(); $i++)
                                        <option value="{{ $i }}" @if ($data['waktu']['tahun'] == $i)
                                            selected
                                        @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @endif
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
                            @forelse ($data['eod'] as $item)
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
  </x-slot>
  <x-slot name="kodejs">
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
  </x-slot>
</x-admin-layout>