<x-admin-layout title="info barang" menu="barang">
  <x-slot name="header">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Informasi Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('barang')}}">Daftar Barang</a></li>
            <li class="breadcrumb-item active">Info Barang</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
  </x-slot>
  <x-slot name="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-header bg-primary">
                <strong>OMZET DALAM BARANG</strong>
              </div>
              <div class="card-body h2 font-weight-bold text-right">
                {{ rupiah($data['omzetdalambarang']) }}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header bg-success">
                <strong>LABA DALAM BARANG</strong>
              </div>
              <div class="card-body h2 font-weight-bold text-right">
                {{ rupiah($data['labadalambarang']) }}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header bg-info">
                <strong>TOTAL OMZET</strong>
              </div>
              <div class="card-body h2 font-weight-bold text-right">
                {{ rupiah($data['totalomzet']) }}
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-secondary">
                <h3 class="card-title">TOP 10 BARANG TERATAS</h3>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-header bg-info">
                          <strong>STOK BARANG TERBANYAK</strong>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-striped small">
                              <thead class="text-uppercase text-center">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                              </thead>
                              <tbody>
                                @foreach ($data['barangstokbanyak'] as $item)
                                  <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-capitalize">{{ $item->nama_barang }}</td>
                                    <td class="text-center">{{ $item->stok }}</td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-header bg-info">
                          <strong>BARANG BANYAK TERJUAL</strong>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-striped small">
                              <thead class="text-uppercase text-center">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Terjual</th>
                              </thead>
                              <tbody>
                                @if (count($data['barangterlaris']) > 0)
                                  @foreach ($data['barangterlaris'] as $item)
                                    <tr>
                                      <td class="text-center">{{ $loop->iteration }}</td>
                                      <td>{{ $item['barang']->nama_barang }}</td>
                                      <td class="text-center">{{ norupiah($item['terjual']) }}</td>
                                    </tr>
                                  @endforeach
                                @else
                                    <tr>
                                      <td class="text-center" colspan="3">Belum ada Data</td>
                                    </tr>
                                @endif
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </x-slot>
</x-admin-layout>