@extends('layouts.admin')

@section('title')
    Data Migrasi Database
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Migrasi Database</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item active">Daftar Migrasi Database</li>
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
                  {{-- <section class="mb-3">
                      <form action="{{ url('listdata') }}" method="get">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <select name="kategori" id="" class="form-control form-control-sm" onchange="this.form.submit();">
                                    <option value="semua">Semua</option>
                                    @foreach (list_kategori() as $item)
                                        <option value="{{ $item}}" @if ($kategori == $item)
                                            selected
                                        @endif>{{ strtoupper($item)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                  </section> --}}
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                           @foreach ($data as $item)
                               <tr>
                                   <td>{{ $loop->iteration }}</td>
                                   <td>{{ $item->kode_barcode }}</td>
                                   <td>{{ $item->nama_barang }}</td>
                                   <td>{{ $item->stok.' '.$item->satuan_barang }}</td>
                                   <td>{{ $item->harga_beli }}</td>
                                   <td>{{ $item->harga_jual }}</td>
                                   <td>{{ $item->kategori }}</td>
                                   <td>{{ $item->status_barang }}</td>
                               </tr>
                           @endforeach
                        </tbody>
                       
                    </table>
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



