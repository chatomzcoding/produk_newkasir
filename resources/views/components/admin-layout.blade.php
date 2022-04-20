<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Cikara Kasir - {{ $title ?? '' }}</title>
  <link href="{{  asset('img/logo.png')}}" rel="icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template/admin/lte/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

  <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('template/admin/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css')}}">

    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert2.css')}}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    {{ $head ?? '' }}

  @livewireStyles

</head>
<body class="hold-transition sidebar-mini layout-fixed" onkeydown="myFunction()">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- SEARCH FORM -->
    @if ($user->level == 'kasir' || $user->level == 'gudang')
    @switch($menu)
        @case('distribusi')
          <form action="{{ url('distribusi') }}" method="get" class="form-inline ml-3">
            <input type="hidden" name="data" value="cari">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" name="cari" type="search" placeholder="Cari Faktur" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
            @break
        @case(2)
            
            @break
        @default
          <form action="{{ url('barang') }}" method="get" class="form-inline ml-3">
            <input type="hidden" name="data" value="cari">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" name="cari" type="search" placeholder="Cari Barang" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
    @endswitch
    @endif

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
         @csrf
         <a href="{{ route('logout') }}"  class="nav-link"
                  onclick="event.preventDefault();
                         this.closest('form').submit();">
        <i class="nav-icon fas fa-sign-out-alt"></i> Keluar
      </a>
      </form>
  </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard')}}" class="brand-link">
      <img src="{{  asset('img/logo.png')}}" alt="CikaraKasir" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><strong>Cikara Kasir</strong></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-1 d-flex">
        <div class="image">
          <img src="{{ asset(avatar($user))}}" class="img-circle elevation-2" alt="{{ avatar($user) }}">
        </div>
        <div class="info">
            <a href="#" class="d-block text-capitalize">{{ $user->name}}</a> <small class="text-white font-italic">{{ $user->level }}</small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ url('/dashboard')}}" class="nav-link small {{ menuaktif($menu,'beranda') }}">
              <i class="nav-icon fas fa-home"></i>
              <p class="text">Beranda</p>
            </a>
          </li>
          @switch($user->level)
              @case('superadmin')
                @include('superadmin.menu')
                @break
              @case('client')
                @include('client.menu')
                @break
              @case('cabang')
                @include('cabang.menu')
                @break
              @case('gudang')
                @include('gudang.menu')
                @break
              @case('kasir')
                @include('kasir.menu')
                @break
              @default
          @endswitch
          <li class="nav-header pt-0">SISTEM</li>
          <li class="nav-item">
            <a href="{{ url('user/'.Crypt::encryptString(Auth::user()->id).'/edit') }}" class="nav-link small {{ menuaktif($menu,'pengaturan') }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p class="text">Pengaturan</p>
            </a>
          </li>
          <li class="nav-item bg-secondary">
              <form method="POST" action="{{ route('logout') }}">
               @csrf
               <a href="{{ route('logout') }}"  class="nav-link text-light small"
                        onclick="event.preventDefault();
                               this.closest('form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i><p>Keluar</p>
            </a>
            </form>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
          <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            {{ $header }}
         
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
        <section class="content">
          {{ $content }}
        </section>
    {{-- @yield('content') --}}
  </div>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; <a href="https://adminlte.io">AdminLTE.io</a>. Pengembang <a href="https://cikarastudio.com/">Cikara Studio</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 2022.01 (beta tester)
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('template/admin/lte/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template/admin/lte/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template/admin/lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
{{-- <script src="{{ asset('template/admin/lte/plugins/chart.js/Chart.min.js')}}"></script> --}}
<!-- Sparkline -->
<script src="{{ asset('template/admin/lte/plugins/sparklines/sparkline.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('template/admin/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('template/admin/lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template/admin/lte/dist/js/adminlte.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('template/admin/lte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
{{-- <script src="{{ asset('template/admin/lte/plugins/jszip/jszip.min.js')}}"></script> --}}
<script src="{{ asset('template/admin/lte/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('template/admin/lte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js')}}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template/admin/lte/dist/js/demo.js')}}"></script>
<script src="{{ asset('js/chatomz.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.select2').select2({
      closeOnSelect: true
    });
  });
  $(function () {
    $('.pop-info').tooltip()
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
      $(".listdata").select2();
  })
</script>
{{ $kodejs ?? '' }}

</body>
</html>
