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
                  <span class="info-box-text">Total Laporan</span>
                  <span class="info-box-number">
                        {{ $data['statistik']['total']}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-cubes"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Item Terjual</span>
                  <span class="info-box-number">
                    {{-- {{ norupiah($statistik['data']['itemterjual'])}} --}}

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
                  <a href="{{ url('datalaporan/transaksi') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-sync"></i> BERSIHKAN FILTER</a>
                  <a href="#" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> TAMBAH RINCIAN</a>
                  <a href="#" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#tutup"><i class="fas fa-close"></i> TUTUP LAPORAN LABA RUGI</a>
                        {{-- <a href="{{ url('cetakdata?s=transaksi&filter=harian&tanggal='.$filter['data']['tanggal'].'&kategori='.$filter['kategori']) }}" target="_blank" class="float-right btn btn-outline-info btn-sm"><i class="fas fa-print"></i> CETAK</a> --}}
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
  
                  <section class="mb-1 mt-1">
                      <form action="{{ url('datalaporan/keuangan') }}" method="get">
                        <div class="row">
                            <div class="col-sm-6 col-md-2">
                                <select name="bulan" id="" class="form-control" onchange="this.form.submit()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" @if ($data['waktu']['bulan'] == $i)
                                            selected
                                        @endif>{{ bulan_indo($i) }}</option>
                                    @endfor
                                </select>
                              </div>
                              <div class="col-sm-6 col-md-2">
                                  <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
                                      @for ($i = 2020; $i <= ambil_tahun(); $i++)
                                          <option value="{{ $i }}" @if ($data['waktu']['tahun'] == $i)
                                              selected
                                          @endif>{{ $i }}</option>
                                      @endfor
                                  </select>
                              </div>
                        </div>
                    </form>
                  </section>
                <section>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            @if ($data['keuangan'])
                                <header class="text-center">
                                    <h2>ZIAN MART</h2>
                                    <h3>Laporan Laba Rugi</h3>
                                    {{ $data['waktu']['info'] }}
                                </header>
                                <main>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="table-secondary text-center">
                                                <tr>
                                                    <th>RINCIAN</th>
                                                    <th>PEMASUKAN</th>
                                                    <th>PENGELUARAN</th>
                                                    <th>JUMLAH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th colspan="4">PENDAPATAN</th>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;&nbsp; Hasil Pendapatan Penjualan per Bulan </td>
                                                    <td class="text-right">{{ norupiah($data['penjualan']) }}</td>
                                                    <td></td>
                                                    <td class="text-right">{{ kolomjumlahkeuangan(['start' => TRUE,'sesi' => 'pemasukan'],$data['penjualan']) }}</td>
                                                </tr>
                                                @foreach (listlaporankeuangan('pendapatan',$data['keuangan']->rincian) as $item)
                                                    @php
                                                        $id = '1'.$loop->iteration
                                                    @endphp
                                                        <form id="data-{{ $id }}" action="{{url('keuangan/'.$data['keuangan']->id)}}" method="post">
                                                            @csrf
                                                            @method('patch')
                                                            <input type="hidden" name="sesi" value="hapusitem">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                    </form>
                                                    <tr>
                                                        <td class="text-capitalize">&nbsp;&nbsp; {{ $item->keterangan }}
                                                            <a onclick="deleteRow( {{ $id }} )" class="float-right btn p-0"><i class="fas fa-trash-alt text-danger"></i></a>
                                                        </td>
                                                        <td class="text-right">{{ norupiah($item->nominal) }}</td>
                                                        <td></td>
                                                        <td class="text-right">{{ kolomjumlahkeuangan(['sesi' => 'pemasukan'],$item->nominal) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="4">PENGELUARAN</th>
                                                </tr>
                                                @foreach (listlaporankeuangan('pengeluaran',$data['keuangan']->rincian) as $item)
                                                    @php
                                                        $id = '2'.$loop->iteration
                                                    @endphp
                                                        <form id="data-{{ $id }}" action="{{url('keuangan/'.$data['keuangan']->id)}}" method="post">
                                                            @csrf
                                                            @method('patch')
                                                            <input type="hidden" name="sesi" value="hapusitem">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                    </form>
                                                <tr>
                                                    <td class="text-capitalize">&nbsp;&nbsp; {{ $item->keterangan }} 
                                                        <a onclick="deleteRow( {{ $id }} )" class="float-right btn p-0"><i class="fas fa-trash-alt text-danger"></i></a>
                                                    </td>
                                                    <td></td>
                                                    <td class="text-right">{{ norupiah($item->nominal) }}</td>
                                                    <td class="text-right">{{ kolomjumlahkeuangan(['sesi' => 'pengeluaran'],$item->nominal) }}</td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <th colspan="4">&nbsp;&nbsp; Operasional</th>
                                                </tr>
                                                @foreach (listlaporankeuangan('operasional',$data['keuangan']->rincian) as $item)
                                                @php
                                                    $id = '3'.$loop->iteration
                                                @endphp
                                                        <form id="data-{{ $id }}" action="{{url('keuangan/'.$data['keuangan']->id)}}" method="post">
                                                            @csrf
                                                            @method('patch')
                                                            <input type="hidden" name="sesi" value="hapusitem">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                    </form>
                                                    <tr>
                                                        <td class="text-capitalize">&nbsp;&nbsp;&nbsp;&nbsp; {{ $item->keterangan }}
                                                            <a onclick="deleteRow( {{ $id }} )" class="float-right btn p-0"><i class="fas fa-trash-alt text-danger"></i></a>
                                                        </td>
                                                        <td></td>
                                                        <td class="text-right">{{ norupiah($item->nominal) }}</td>
                                                        <td class="text-right">{{ kolomjumlahkeuangan(['sesi' => 'pengeluaran'],$item->nominal) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="4">&nbsp;&nbsp; Pembelian</th>
                                                </tr>
                                                @foreach (listlaporankeuangan('pembelian',$data['keuangan']->rincian) as $item)
                                                    @php
                                                        $id = '4'.$loop->iteration
                                                    @endphp
                                                    <tr>
                                                        <form id="data-{{ $id }}" action="{{url('keuangan/'.$data['keuangan']->id)}}" method="post">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <input type="hidden" name="sesi" value="hapusitem">
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                            </form>
                                                        <td class="text-capitalize">&nbsp;&nbsp;&nbsp;&nbsp; {{ $item->keterangan }}
                                                            <a onclick="deleteRow( {{ $id }} )" class="float-right btn p-0"><i class="fas fa-trash-alt text-danger"></i></a>
                                                        </td>
                                                        <td></td>
                                                        <td class="text-right">{{ norupiah($item->nominal) }}</td>
                                                        <td class="text-right">{{ kolomjumlahkeuangan(['sesi' => 'pengeluaran'],$item->nominal) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="table-info">
                                                    <th colspan="3">TOTAL PENDAPATAN AKHIR BULAN</th>
                                                    <th class="text-right">{{ kolomjumlahkeuangan(['stop' => TRUE]) }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </main>
                            @else
                                <div class="alert alert-info text-center">
                                    <strong>Belum ada laporan untuk {{ $data['waktu']['info'] }}</strong> <br>
                                    <form action="{{ url('keuangan') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="bulan" value="{{ $data['waktu']['bulan'] }}">
                                        <input type="hidden" name="cabang_id" value="{{ $data['akses']->cabang_id }}">
                                        <input type="hidden" name="tahun" value="{{ $data['waktu']['tahun'] }}">
                                        <button type="submit" class="btn btn-primary btn-sm">Tambahkan Laporan Keuangan</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </section> 
              </div>
            </div>
          </div>
        </div>
    </div>
    @if ($data['keuangan'])
        <div class="modal fade" id="tambah">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('/keuangan')}}" method="post">
                    @csrf
                    <input type="hidden" name="sesi" value="rincian">
                    <input type="hidden" name="keuangan_id" value="{{ $data['keuangan']->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Rincian</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <section class="p-3">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Kategori Rincian</label>
                            <select name="kategori" id="" class="form-control col-md-8">
                                <option value="pembelian">PEMBELIAN</option>
                                <option value="operasional">BEBAN OPERASIONAL</option>
                                <option value="pengeluaran">PENGELUARAN LAINNYA</option>
                                <option value="pendapatan">PENDAPATAN LAIN LAIN</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Keterangan Rincian {!! ireq() !!}</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}" class="form-control col-md-8" required>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Nominal {!! ireq() !!}</label>
                            <input type="text" name="nominal" id="rupiah" value="{{ old('nominal') }}" class="form-control col-md-8" required>
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
    @endif
    <!-- /.modal -->

    {{-- modal edit --}}
    <div class="modal fade" id="ubah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('keuangan.update','test')}}" method="post">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit Data Satuan Barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="id" id="id">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Kategori {!! ireq() !!}</label>
                        <input type="text" name="nama" id="nama" class="form-control col-md-8" required>
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
                var keterangan = button.data('keterangan')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #nama').val(nama);
                modal.find('.modal-body #keterangan').val(keterangan);
                modal.find('.modal-body #id').val(id);
            })
        </script>
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
    @endsection

    @endsection

