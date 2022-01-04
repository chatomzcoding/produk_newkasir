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
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-cart"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Total Transaksi</span>
                  <span class="info-box-number">
                        {{ $statistik['data']['total']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Item Terjual</span>
                  <span class="info-box-number">
                    {{ $statistik['data']['itemterjual']}}

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
                  <span class="info-box-text">Total Omzet</span>
                  <span class="info-box-number">
                    {{ rupiah($statistik['data']['omzet'])}}

                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Laba</span>
                  <span class="info-box-number">
                    {{ rupiah($statistik['data']['laba'])}}

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
                  <a href="{{ url('transaksi') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-sync"></i> BERSIHKAN FILTER</a>
                  {{-- <a href="{{ url('cetakdata?s=transaksi&tanggal='.$tanggal) }}" target="_blank" class="float-right btn btn-outline-info btn-sm"><i class="fas fa-print"></i> CETAK</a> --}}
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
  
                  <section class="mb-1 mt-1">
                      <form action="{{ url('laporan/transaksi') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="s" id="" class="form-control" onchange="this.form.submit()">
                                    <option value="harian" @if ($filter['s'] == 'harian')
                                        selected
                                    @endif>Harian</option>
                                    <option value="bulanan" @if ($filter['s'] == 'bulanan')
                                        selected
                                    @endif>Bulanan</option>
                                    <option value="tahunan" @if ($filter['s'] == 'tahunan')
                                        selected
                                    @endif>Tahunan</option>
                                </select>
                            </div>
                            @switch($filter['s'])
                                @case('harian')
                                    @include('sistem.laporan.filter.harian')
                                    @break
                                @case('bulanan')
                                    @include('sistem.laporan.filter.bulanan')
                                    @break
                                @case('tahunan')
                                    @include('sistem.laporan.filter.tahunan')
                                    @break
                                @default
                                    
                            @endswitch
                        </div>
                    </form>
                  </section>
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
                                        <form id="data-{{ $item->id }}" action="{{url('transaksi/'.$item->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            </form>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                  <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if ($item->status_transaksi == 'selesai')
                                                      <a href="{{ url('transaksi/'.Crypt::encryptString($item->id)) }}" class="dropdown-item">  INVOICE <span class="float-right"><i class="fas fa-receipt text-primary"></i></span></a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item">HAPUS<span class="float-right"><i class="fas fa-trash-alt text-danger"></i></span></button>
                                                </div>
                                            </div>
                                    </td>
                                    <td>{{ $item->kode_transaksi}}</td>
                                    <td>{{ $item->tipe_pembayaran}}</td>
                                    <td>{{ $item->tipe_orderan}}</td>
                                    <td>{{ rupiah($item->uang_pembeli)}}</td>
                                    <td>
                                        @if (!is_null($item->keranjang))
                                            <ul class="list-group">
                                                @foreach (json_decode($item->keranjang) as $key)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                                        {{ $key->nama_barang }}
                                                        <span class="badge badge-secondary badge-pill">{{ $key->jumlah }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="text-center">{!! statustransaksi($item->status_transaksi)!!}</td>
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
    @section('script')
        <script>
            $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                // "buttons": ["copy","excel"]
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
          <script type="text/javascript">
            function myFunction(){
                /* tombol enter */
                if(event.keyCode == 13) {
                    event.preventDefault()
                    $("#tambahtransaksi").click();
                }
            } 
        </script>
    @endsection

    @endsection

