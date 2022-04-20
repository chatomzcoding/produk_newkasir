<x-admin-layout title="dashboard" menu="beranda">
  <x-slot name="header">
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
  </x-slot>
  <x-slot name="content">
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-cube"></i></span>
    
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
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-luggage-cart"></i></span>
    
              <div class="info-box-content">
                <span class="info-box-text">Total Distribusi</span>
                <span class="info-box-number">
                  {{ $statistik['totaldistribusi'] }}
    
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tag"></i></span>
    
              <div class="info-box-content">
                <span class="info-box-text">Total Supplier</span>
                <span class="info-box-number">
                  {{ $statistik['totalsupplier'] }}
    
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
    
              <div class="info-box-content">
                <span class="info-box-text">Barang Stok Kosong</span>
                <span class="info-box-number">
                  {{ $statistik['totalbarangstokkosong'] }}
    
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        {{-- @include('admin.chart') --}}
      </div>
    </section>
  </x-slot>
</x-admin-layout>