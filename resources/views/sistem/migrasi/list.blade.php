@extends('layouts.admin')

@section('title')
    Data Migrasi Database {{ $sesi }}
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Migrasi Database {{ $sesi }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('migrasi')}}">Migrasi</a></li>
            <li class="breadcrumb-item active">Daftar Migrasi Database {{ $sesi }}</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
   
@section('container')
    
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('page/'.$sesi.'?s=simpan') }}" class="btn btn-primary btn-sm">SIMPAN</a>
                </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                  <div class="table-responsive">
                      @switch($sesi)
                            @case('barang')
                                @include('sistem.migrasi.tabel.barang')
                                @break
                            @case('supplier')
                                @include('sistem.migrasi.tabel.supplier')
                                @break
                            @case('distribusi')
                                @include('sistem.migrasi.tabel.distribusi')
                                @break
                            @case('transaksi')
                                @include('sistem.migrasi.tabel.transaksi')
                                @break
                            @case('user')
                                @include('sistem.migrasi.tabel.user')
                                @break
                          @default
                      @endswitch
                    
                    @if ($sesi <> 'user')
                        @if ($data->hasPages())
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($data->onFirstPage())
                                        <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
                                    @else
                                        <li><a href="{{ $data->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
                                    @endif
                        
                                    {{-- Next Page Link --}}
                                    @if ($data->hasMorePages())
                                        <li><a href="{{ $data->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
                                    @else
                                        <li class="disabled" aria-disabled="true"><span>@lang('pagination.next')</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    @section('script')
        
        <script>
            $('#ubah').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var nama = button.data('nama')
                var kategori = button.data('kategori')
                var keterangan = button.data('keterangan')
                var id = button.data('id')
        
                var modal = $(this)
        
                modal.find('.modal-body #nama').val(nama);
                modal.find('.modal-body #kategori').val(kategori);
                modal.find('.modal-body #keterangan').val(keterangan);
                modal.find('.modal-body #id').val(id);
            })
        </script>
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
    @endsection

    @endsection



