@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('header')
  <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('head')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection

@section('container')
    
  
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-cart"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi</span>
                    <span class="info-box-number">
                        {{ $statistik['totaltransaksi'] }}
                      {{-- <small>%</small> --}}
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cube"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Total Barang</span>
                    <span class="info-box-number">
                      {{ $statistik['totalbarang'] }}

                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
    
              <!-- fix for small devices only -->
              <div class="clearfix hidden-md-up"></div>
    
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cart-arrow-down"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Transaksi Hari ini</span>
                    <span class="info-box-number">
                      {{ $statistik['totaltransaksihariini'] }}

                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-bill-wave-alt"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Omzet Hari ini</span>
                    <span class="info-box-number">
                      {{ $statistik['totalomzethariini'] }}

                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Statistik Omzet Transaksi
                  </div>
                  <div class="card-body">
                    <figure class="highcharts-figure">
                      <div id="container"></div>
                      <p class="highcharts-description">
                          Omzet didapat harian hasil dari penjumlahan barang dan harga jual per item
                      </p>
                      <table id="datatable" style="display: none">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th>Tanggal</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($chart['omzet-harian'] as $item)
                              <tr>
                                  <th>{{ $item['tanggal'] }}</th>
                                  <td>{{ $item['nilai'] }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                      </table>
                  </figure>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
    @endsection

    @section('script')
        <script>
          Highcharts.chart('container', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Grafik Omzet Transaksi per Hari'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Omzet'
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
            this.point.y + ' | ' + this.point.name.toLowerCase();
        }
    }
});
        </script>
    @endsection