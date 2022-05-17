<x-admin-layout title="Daftar Barang" menu="barang">
  <x-slot name="header">
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
  </x-slot>
  <x-slot name="content">
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- statistik -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-box-open"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Barang</span>
                      <span class="info-box-number">
                        {{ $statistik['totalbarang'] }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cubes"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Item</span>
                      <span class="info-box-number">
                        {{ $statistik['totalitem'] }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
      
                    <div class="info-box-content">
                      <span class="info-box-text">Total Barang Stok Kosong</span>
                      <span class="info-box-number">
                        {{ $statistik['totalbarangstokkosong'] }}
                        {{-- <small>%</small> --}}
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </div>
              @if (count($kategori) == 0)
                  <div class="alert alert-info">
                    Sebelum menambahkan barang, silahkan tambahkan kategori dan satuan terlebih dahulu <br>
                    <a href="{{ url('kategori') }}">Tambah Kategori</a> || <a href="{{ url('satuan') }}">Tambah Satuan</a>
                  </div>
              @endif
            <div class="card">
              <div class="card-header">
                  @if ($user->level == 'gudang')
                    @if (count($kategori) > 0)
                      <a href="{{ url('barang/create') }}" class="btn btn-outline-primary btn-sm pop-info" title="Tambah Barang Baru"><i class="fas fa-plus"></i> Tambah</a>
                      @else
                      <a href="#" class="btn btn-outline-primary btn-sm pop-info disabled" title="Tambah Barang Baru"><i class="fas fa-plus"></i> Tambah</a>
                    @endif
                    <a href="{{ url('barang') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-sync"></i> Bersihkan Filter</a>
                  @endif
                    <div class="float-right">
                      @if ($user->level == 'gudang')
                        <div class="btn-group">
                          <button type="button" class="btn btn-info btn-sm ">Option</button>
                          <button type="button" class="btn btn-info btn-sm  dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                            <div class="dropdown-menu" role="menu">
                              @if (isset($_GET['kategori']))
                                <a href="{{ url('cetakdata?s=barang&kategori='.$filter['kategori'].'&harga=TRUE') }}" class="dropdown-item pop-info" target="_blank" title="Cetak daftar harga barang"> CETAK HARGA <i class="fas fa-print float-right text-info pt-1"></i></a>
                                <div class="dropdown-divider"></div>
                              @endif
                              <form action="{{url('/barang/semua')}}" method="post">
                                @csrf
                                @method('delete')
                                  <button class="dropdown-item" type="submit">Hapus Semua <i class="fas fa-trash-alt float-right text-danger pt-1"></i></button>
                                </form>
                            </div>
                        </div>
                      @endif
                      @if (isset($_GET['kategori']))
                        <a href="{{ url('cetakdata?s=barang&kategori='.$filter['kategori']) }}" class="btn btn-outline-info btn-sm pop-info" target="_blank" title="Cetak daftar barang"><i class="fas fa-print"></i> CETAK</a>
                      @endif
                    </div>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <section class="mb-3">
                    <form action="{{ url('barang') }}" method="get">
                      @csrf
                      <div class="row">
                          <div class="form-group col-md-2">
                              <select name="kategori" id="" class="form-control listdata" width="100%" onchange="this.form.submit();">
                                  <option value="semua">-- Semua Kategori</option>
                                  @foreach ($kategori as $item)
                                      <option value="{{ $item->id}}" @if ($filter['kategori'] == $item->id)
                                          selected
                                      @endif>{{ strtoupper($item->nama)}}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-md-10">
                            <section class="font-italic text-right">
                              @if (isset($_GET['cari']))
                              Pencarian dengan keyword : <strong>{{ $_GET['cari'] }}</strong> [ditemukan - <strong>{{ count($barang) }} barang</strong>]
                              @endif
                              @if (isset($_GET['kategori']))
                              Kategori : <strong>{{ DbCikara::namaKategori($_GET['kategori']) }}</strong> [ditemukan - <strong>{{ count($barang) }} barang</strong>]
                              @endif
                            </section>
                          </div>
                      </div>
                  </form>
                </section>
                  <div class="table-responsive">
                    <table id="{{ cekdatatable($filter['page']) }}" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                @if ($user->level == 'gudang')
                                <th width="12%">Aksi</th>
                                @endif
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    @if ($user->level == 'gudang')
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
                                            <a href="{{ url('barang/'.Crypt::encryptString($item->id)) }}" class="dropdown-item">DETAIL <span class="float-right"><i class="fas fa-file text-primary"></i></span></a>
                                            <div class="dropdown-divider"></div>
                                            <button onclick="deleteRow( {{ $item->id }} )" class="dropdown-item">HAPUS <span class="float-right"><i class="fas fa-trash-alt text-danger"></i></span></button>
                                          </div>
                                        </div>
                                      </td>
                                    @endif
                                    <td>{{ $item->kode_barang }}</td>
                                    <td class="text-capitalize">{{ $item->nama_barang }}</td>
                                    <td class="text-right">{{ norupiah($item->harga_beli) }} </td>
                                    <td class="text-right">{{ norupiah($item->harga_jual) }} </td>
                                    <td class="text-center">{{ $item->stok.' '.$item->satuan->nama }} </td>
                                    <td class="text-center text-uppercase">{{ $item->kategori->nama }}</td>
                                </tr>
                            @endforeach
                    </table>
                     @includeWhen($filter['page'], 'sistem.pagination', ['link' => NULL,'datatabel'=>$barang])
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
  </x-slot>
</x-admin-layout>