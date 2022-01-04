@extends('layouts.admin')

@section('title')
    Data Retur
@endsection


@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Retur - {{ $retur->kode_retur }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('retur')}}">Daftar Retur</a></li>
            <li class="breadcrumb-item active">Detail Retur</li>
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
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    <a href="{{ url('retur') }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali Ke Daftar Distribusi"><i class="fas fa-angle-left"></i> Kembali</a>
                    <div class="float-right">
                        {!! showstatus($retur->status_retur) !!}
                    </div>

              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <table width="100%">
                                        <tr>
                                            <th class="h4">Data Retur</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Tanggal Retur</strong> <br> &nbsp;&nbsp;
                                                {{ date_indo($retur->tgl_retur) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="h4"><hr> Data Distribusi</th>
                                        </tr>
                                        <tr>
                                            <td> <strong>No Faktur/Bukti Pembelian</strong> <br> &nbsp;&nbsp;
                                            {{ $distribusi->no_faktur }}</td>
                                        </tr>
                                        <tr>
                                            <td> <strong>Tanggal Faktur/Pembelian</strong> <br> &nbsp;&nbsp;
                                            {{ date_indo($distribusi->tgl_faktur) }}</td>
                                        </tr>
                                        <tr>
                                            <td> <strong>Potongan</strong> <br> &nbsp;&nbsp;
                                            {{ rupiah($distribusi->potongan) }}</td>
                                        </tr>
                                        <tr>
                                            <td> <strong>Total Pembayaran</strong> <br> &nbsp;&nbsp;
                                                {{ rupiah(totalhargadistribusi($distribusi->barang)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    @if ($retur->status_retur == 'proses')
                                        <section class="text-right mb-2">
                                            <a href="" data-toggle="modal" data-target="#returbarang" class="btn btn-success">RETUR BARANG</a>
                                        </section>
                                    @endif
                                    <div class="table-responsive">
                                        <h4>Daftar List Barang Distribusi</h4>
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead class="text-center">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Retur</th>
                                                    @if ($retur->status_retur == 'proses')
                                                    <th>Aksi</th>
                                                        
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody class="text-capitalize">
                                                @forelse (json_decode($distribusi->barang) as $item)
                                                <tr>
                                                        <td class="text-center">{{ $loop->iteration}}</td>
                                                        <td>{{ $item->kode_barang}}</td>
                                                        <td>{{ $item->nama_barang}}</td>
                                                        <td class="text-right">{{ norupiah($item->harga_beli)}} <br>
                                                        {{ norupiah($item->harga_jual)}}</td>
                                                        <td class="text-center">{{ $item->jumlah}}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $jumlahretur = jumlahretur($retur->barang_retur,$item->kode_barang);
                                                            @endphp
                                                            {{ $jumlahretur }}
                                                        </td>
                                                        @if ($retur->status_retur == 'proses')
                                                            <td> <button type="button" data-toggle="modal" data-nama_barang="{{ $item->nama_barang }}" data-jumlah="{{ $item->jumlah }}" data-jumlahretur="{{ datajumlahretur($jumlahretur,$item->jumlah) }}"  data-kode_barang="{{ $item->kode_barang }}" data-target="#ubah" title="" class="btn btn-warning btn-sm" data-original-title="Edit Task"><i class="fas fa-redo"> retur</i>
                                                                </button></td>
                                                        @endif
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="6">tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <hr>
                 
              </div>
            </div>
          </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div class="modal fade" id="ubah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('retur.update','test')}}" method="post">
                @csrf
                @method('patch')
            <div class="modal-header">
            <h4 class="modal-title">Edit Data List Retur Barang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="s" value="editbarang">
                <input type="hidden" name="id" value="{{ $retur->id }}">
                <input type="hidden" name="kode_barang" id="kode_barang">
                <input type="hidden" name="nama_barang" id="nama_barang">
                <section class="p-3">
                    <div class="form-group row">
                        <label for="" class="col-md-4">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control col-md-8" readonly>
                    </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Jumlah Pembelian</label>
                        <input type="number" name="jumlah" id="jumlah" min="1" class="form-control col-md-8" readonly>
                    </div>
                   <div class="form-group row">
                        <label for="" class="col-md-4">Jumlah Retur</label>
                        <input type="number" name="jumlahretur" id="jumlahretur" min="1" max="" class="form-control col-md-8" required>
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

    <div class="modal fade" id="returbarang">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('retur.update','test')}}" method="post">
                @csrf
                @method('patch')
                <input type="hidden" name="s" value="returbarang">
                <input type="hidden" name="id" value="{{ $retur->id }}">
                <input type="hidden" name="status_retur" value="selesai">
            <div class="modal-header">
                <h4 class="modal-title">Retur Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <section class="p-3">
                   <div class="callout callout-info">
                    barang yang sudah diretur tidak bisa diedit kembali
                   </div>
                </section>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> RETUR SEKARANG</button>
            </div>
        </form>
        </div>
        </div>
    </div>

    @section('script')
        
        <script>
            $('#ubah').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var jumlah = button.data('jumlah')
                var jumlahretur = button.data('jumlahretur')
                var harga_beli = button.data('harga_beli')
                var harga_jual = button.data('harga_jual')
                var kode_barang = button.data('kode_barang')
                var nama_barang = button.data('nama_barang')
        
                var modal = $(this)
        
                modal.find('.modal-body #jumlah').val(jumlah);
                modal.find('.modal-body #rupiah').val(harga_beli);
                modal.find('.modal-body #jumlahretur').attr('max',jumlah);
                modal.find('.modal-body #jumlahretur').val(jumlahretur);
                modal.find('.modal-body #kode_barang').val(kode_barang);
                modal.find('.modal-body #nama_barang').val(nama_barang);
            })
        </script>
        <script src="{{ asset('js/jquery-3.2.1.min.js')}}"></script>
        <script src="{{ asset('js/jquery.autocomplete.min.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
    
                // Selector input yang akan menampilkan autocomplete.
                $( "#nama" ).autocomplete({
                    serviceUrl: "{{route('loadBarang')}}",   // Kode php untuk prosesing data.
                    dataType: "JSON",           // Tipe data JSON.
                    onSelect: function (suggestion) {
                        $( "#nama" ).val("" + suggestion.nama);
                    }
                });
            });
    
        </script>
            <script type="text/javascript">
                function myFunction(){
                    /* tombol shift R */
                    if(event.keyCode == 16) {
                        event.preventDefault()
                        $("#tambahbarang").click();
                    }
                }
            </script>
    @endsection

    @endsection

