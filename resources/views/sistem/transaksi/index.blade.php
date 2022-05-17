<x-admin-layout title="Data Transaksi" menu="transaksi">
  <x-slot name="header">
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
  </x-slot>
  <x-slot name="content">
    <div class="container-fluid">
      <div class="row">
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Transaksi</span>
                <span class="info-box-number">
                      {{ $statistik['total']}}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Transaksi Hari ini</span>
                <span class="info-box-number">
                      {{ $statistik['totalhariini']}}
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
                <div class="float-right">
                  @if ($laporan)
                    <a href="#" class="btn btn-outline-dark btn-sm"><i class="fas fa-file"></i> TRANSAKSI SUDAH DITUTUP</a>
                    <a href="" onclick="onClick()" class="btn btn-outline-primary btn-sm"><i class="fas fa-print"></i> CETAK LAPORAN</a>
                  @else
                    @if (count($transaksi) > 0)
                      <a href="#" data-toggle="modal" data-target="#laporan" class="btn btn-outline-success btn-sm"><i class="fas fa-file"></i> TUTUP TRANSAKSI</a>
                    @endif
                  @endif
                  @if (count($transaksi) > 0)
                    <a href="{{ url('cetakdata?s=transaksi&tanggal='.$tanggal) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fas fa-print"></i> CETAK</a>
                  @endif

                </div>
            </div>
            <div class="card-body">
                @include('sistem.notifikasi')
                <section class="mb-1 mt-1">
                      <div class="row">
                        @if ($user->level == 'kasir')
                          @if (!$laporan AND $tanggal == tgl_sekarang())
                            <div class="col-md-3">
                                @if ($cektransaksi)
                                    <form action="{{ url('transaksi/'.Crypt::encryptString($cektransaksi->id)) }}" method="get">
                                      <button type="submit" class="btn btn-outline-info btn-sm pop-info" title="Tambah Transaksi" id="tambahtransaksi"><i class="fas fa-sync"></i> Lanjutkan Transaksi [Enter]</button>
                                    </form>
                                @else
                                <form action="{{ url('transaksi') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="sesi" value="tambah">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="kode_transaksi" value="{{ DbCikara::kodeTransaksi($user->id) }}">
                                    <input type="hidden" name="status_transaksi" value="proses">
                                    <input type="hidden" name="uang_pembeli" value="0">
                                    <button type="submit" class="btn btn-outline-primary btn-flat btn-sm pop-info" title="Tambah Transaksi" id="tambahtransaksi"><i class="fas fa-plus"></i> Tambah Transaksi Baru [Enter]</button>
                                </form>
                              @endif
                            </div>
                          @endif
                        @endif
                        <div class="form-group col-md-2">
                            <form action="{{ url('transaksi') }}" method="get">
                              <input type="date" name='tanggal' class="form-control" value="{{ $tanggal }}" max="{{ tgl_sekarang() }}" onchange="this.form.submit()">
                            </form>
                          </div>
                      </div>
                </section>
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                      <thead class="text-center">
                          <tr>
                              <th width="5%">No</th>
                              <th width="10%">Aksi</th>
                              <th>Kode Transaksi</th>
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
                                                  @if ($item->status_transaksi == 'proses')
                                                    <a href="{{ url('transaksi/'.Crypt::encryptString($item->id)) }}" class="dropdown-item">  LANJUTKAN <span class="float-right"><i class="fas fa-shopping-cart text-info"></i></span></a>
                                                  @else
                                                    <a href="{{ url('transaksi/'.Crypt::encryptString($item->id)) }}" class="dropdown-item">  INVOICE <span class="float-right"><i class="fas fa-receipt text-primary"></i></span></a>
                                                  @endif
                                                @if ($tanggal == tgl_sekarang())
                                                  <div class="dropdown-divider"></div>
                                                  <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item">HAPUS<span class="float-right"><i class="fas fa-trash-alt text-danger"></i></span></button>
                                                @endif
                                              </div>
                                          </div>
                                  </td>
                                  <td>{{ $item->kode_transaksi}}</td>
                                  <td class="text-right">{{ rupiah($item->uang_pembeli)}}</td>
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
  <div class="modal fade" id="laporan">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ url('/laporan')}}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="tgl_laporan" value="{{ $tanggal }}">
        <div class="modal-header">
        <h4 class="modal-title">INFORMASI TUTUP LAPORAN TANGGAL {{ date_indo($tanggal) }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body p-3">
            <section class="p-3">
                <div class="form-group row">
                    <label for="" class="col-md-4">Modal Awal</label>
                    <input type="text" name="modal" id="rupiah" value="{{ old('modal') }}" class="form-control col-md-8" required>
                  </div>
                <div class="form-group row">
                    <label for="" class="col-md-4">Pengeluaran Kebutuhan</label>
                    <input type="text" name="pengambilan" id="rupiah1" value="{{ old('pengambilan') }}" class="form-control col-md-8" required>
                </div>
            </section>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> SIMPAN LAPORAN</button>
        </div>
    </form>
    </div>
    </div>
</div>
  </x-slot>
  <x-slot name="kodejs">
    @php
    if ($user->level == 'kasir') {
        $alamat = explode('-',$client->alamat);
        if ($laporan) {
            $sisa   = $laporan->total_penjualan + $laporan->total_modal - $laporan->total_pengambilan;
        }
    }
    @endphp
    @if ($laporan)
      <script src="{{ asset('js/recta.js')}}"></script>
      <script type="text/javascript">
          var printer = new Recta('{{ $user->akses->app_key }}', '{{ $user->akses->app_port }}')

          function onClick () {
            printer.open().then(function () {
                printer.align('center')
                      .text('LAPORAN KASIR')
                      .text('-------------------------------')
                      .bold(true)
                      .text('')
                      .text('{{ strtoupper($client->nama_toko)}}')
                      .bold(false)
                      @foreach ($alamat as $item)
                      .text('{{ $item}}')
                      @endforeach
                      .text('-------------------------------')
                      // .underline(true)
                      .align('left')
                      .text('No. Mesin Kasir')
                      .align('right')
                      .text('CKR-001')
                      .align('left')
                      .text('Tanggal Laporan')
                      .align('right')
                      .text('{{ date_indo($laporan->tgl_laporan)}}')
                      .align('left')
                      .text('Total Transaksi')
                      .align('right')
                      .text('{{ $laporan->total_transaksi}}')
                      .align('left')
                      .text('Total Penjualan')
                      .align('right')
                      .text('{{ rupiah($laporan->total_penjualan)}}')
                      .align('left')
                      .text('Total Laba')
                      .align('right')
                      .text('{{ rupiah($laporan->total_laba)}}')
                      .align('left')
                      .text('Total Penyertaan Modal')
                      .align('right')
                      .text('{{ rupiah($laporan->total_modal)}}')
                      .align('left')
                      .text('Total Pengambilan Tunai')
                      .align('right')
                      .text('{{ rupiah($laporan->total_pengambilan)}}')
                      .align('left')
                      .text('Total Kas Tersisa')
                      .align('right')
                      .text('{{ rupiah($sisa)}}')
                      .align('center')
                      .text('-------------------------------')
                      .align('right')
                      .text('KASIR ')
                      .text('{{ $user->name}}')
                      .align('center')
                      .text('-------------------------------')
                      .text('')
                      @php
                          $total = 0;
                      @endphp
                      @foreach (DbCikara::showtable('users',['level','kasir']) as $item)
                          @php
                              $pendapatan     = DbCikara::omzetByUser('hariini',['user_id' => $item->id]);
                              $total = $total + $pendapatan;
                          @endphp
                      .align('left')
                      .text('Total Penjualan : {{ $item->name}}')
                      .align('right')
                      .text('{{ rupiah($pendapatan)}}')
                      @endforeach
                      .align('left')
                      .text('Total Keseluruhan : {{ rupiah($total)}}')
                      .cut()
                .print()
            })
          }
      </script>
    @endif
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
    <script type="text/javascript">
      function myFunction(){
          /* tombol enter */
          if(event.keyCode == 13) {
              event.preventDefault()
              $("#tambahtransaksi").click();
          }
      } 
  </script>
  </x-slot>
</x-admin-layout>