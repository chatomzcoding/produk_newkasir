<x-admin-layout title="detail distribusi" menu="distribusi">
    <x-slot name="head">
        <style>
            input[type=text] {
                border: 1px solid #bdbdbd;
                font-family: 'Roboto', Arial, Sans-serif;
                font-size: 15px;
                font-weight: 400;
                padding: .5em .75em;
                width: 100%;
            }
            input[type=text]:focus {
                border: 2px solid #757575;
                outline: none;
            }
            .autocomplete-suggestions {
                border: 1px solid #999;
                background: #FFF;
                overflow: auto;
            }
            .autocomplete-suggestion {
                padding: 2px 5px;
                white-space: nowrap;
                overflow: hidden;
            }
            .autocomplete-selected {
                background: #F0F0F0;
            }
            .autocomplete-suggestions strong {
                font-weight: normal;
                color: #3399FF;
            }
            .autocomplete-group {
                padding: 2px 5px;
            }
            .autocomplete-group strong {
                display: block;
                border-bottom: 1px solid #000;
            }
        </style>
    </x-slot>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Data Distribusi - {{ $distribusi->kode_distribusi }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('distribusi')}}">Daftar Distribusi</a></li>
                <li class="breadcrumb-item active">Detail Distribusi</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </x-slot>
    <x-slot name="content">
        <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                        <a href="{{ url('distribusi') }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali Ke Daftar Distribusi"><i class="fas fa-angle-left"></i> Kembali</a>
                            @if ($distribusi->pelunasan == 'belum lunas' AND $distribusi->status_stok == 'selesai')
                                <a href="#" data-toggle="modal" data-target="#bayar" class="btn btn-info btn-sm"><i class="fas fa-money-bill-wave-alt"></i> Lakukan Pembayaran</a>
                            @endif
                        <div class="float-right">
                            {!! showstatus($distribusi->status_stok) !!}
                        </div>
                  </div>
                  <div class="card-body">
                      @include('sistem.notifikasi')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <th>No Faktur/Bukti Pembelian</th>
                                                <td>{{ $distribusi->no_faktur }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Faktur/Pembelian</th>
                                                <td>{{ date_indo($distribusi->tgl_faktur) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Jatuh Tempo</th>
                                                <td>{{ date_indo($distribusi->tgl_tempo) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Supplier</th>
                                                <td>{{ $supplier->nama_supplier }}</td>
                                            </tr>
                                            <tr>
                                                <th>Potongan</th>
                                                <td>{{ rupiah($distribusi->potongan) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Pembayaran</th>
                                                <td>{{ rupiah($data['totalpembayaran']) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-info">
                                    <div class="card-body p-2">
                                        PEMBAYARAN
                                        <div class="float-right text-uppercase">
                                            {{ $distribusi->pembayaran }} 
                                            @if ($distribusi->pelunasan == 'lunas')
                                                [LUNAS]
                                            @else
                                                [BELUM LUNAS]
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        @if ($distribusi->status_stok == 'proses')
                                            <section class="text-right">
                                                <a href="" data-toggle="modal" data-target="#distribusikan" class="btn btn-success"><i class="fas fa-truck-loading"></i> DISTRIBUSIKAN</a>
                                            </section>
                                            <hr>
                                        <form action="{{ url('distribusi/'.Crypt::encryptString($distribusi->id)) }}" method="get">
                                            @csrf
                                            <input type="hidden" name="s" value="tambahbarang">
                                            <div class="form-group">
                                                    <label for="">Tambah Barang Distribusi</label>
                                                    <input type="text" name="barcode" class="form-control" placeholder="scan barcode disini" autofocus>
                                                    <input type="text" id="nama" name="nama_barang" class="form-control" placeholder="Cari barang dengan Nama" value="">
                                                </div>
                                                <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary btn-sm" id="tambahbarang"><i class="fas fa-search"></i> CARI BARANG [Shift-R]</button>
                                            </div>
                                        </form>
                                        @else
                                            <div class="callout callout-info">
                                                Barang telah di distribusikan. Stok barang telah otomatis bertambah sesuai dengan jumlah barang pada list barang distribusi
                                            </div>
                                            @if ($retur)
                                                @if ($retur->status_retur == 'proses')
                                                <div class="callout callout-warning">
                                                    Distribusi dalam proses pengembalian barang (retur barang). <a href="{{ url('retur/'.Crypt::encryptString($retur->id)) }}">Lihat Disini</a>
                                                </div>
                                                @else
                                                <div class="callout callout-warning">
                                                    Proses Retur barang telah selesai dilakukan. <a href="{{ url('retur/'.Crypt::encryptString($retur->id)) }}">Lihat Disini</a>
                                                </div>
                                                @endif
                                            @else
                                                <div class="callout callout-warning">
                                                    Apabila barang ingin dikembalikan ke supplier (retur barang). <a href="" data-toggle="modal" data-target="#tambah">klik Disini</a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>                
                        <hr>
                      <div class="table-responsive">
                        <h4>Daftar List Barang Distribusi <span class="float-right badge badge-info">Total harga Barang : {{ norupiah($data['totalhargabarang']) }}</span></h4>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    @if ($distribusi->status_stok == 'proses')
                                        <th width="10%">Aksi</th>
                                    @endif
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="text-capitalize">
                                @if (!is_null($distribusi->barang))
                                    @foreach (json_decode($distribusi->barang) as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration}}</td>
                                        @if ($distribusi->status_stok == 'proses')
                                            <td class="text-center">
                                                <form id="data-{{ $loop->iteration }}" action="{{url('distribusi/'.$loop->iteration)}}" method="post">
                                                    @csrf
                                                    @method('patch')
                                                    <input type="hidden" name="s" value="hapusbarang">
                                                    <input type="hidden" name="id" value="{{ $distribusi->id }}">
                                                    <input type="hidden" name="kode_barang" value="{{ $item->kode_barang }}">
                                                    <input type="hidden" name="nama_barang" value="{{ $item->nama_barang }}">
                                                    </form>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm btn-flat">Aksi</button>
                                                        <button type="button" class="btn btn-info btn-sm btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            <button type="button" data-toggle="modal" data-nama_barang="{{ $item->nama_barang }}" data-harga_jual="{{ $item->harga_jual }}"  data-harga_beli="{{ $item->harga_beli }}" data-jumlah="{{ $item->jumlah }}"  data-kode_barang="{{ $item->kode_barang }}" data-target="#ubah" title="" class="dropdown-item" data-original-title="Edit Task">
                                                            EDIT <i class="fa fa-edit float-right text-success"></i>
                                                            </button>
                                                        <div class="dropdown-divider"></div>
                                                        <button onclick="deleteRow( {{ $loop->iteration }} )" class="dropdown-item"> HAPUS <i class="fas fa-trash-alt float-right text-danger"></i></button>
                                                        </div>
                                                    </div>
                                            </td>
                                        @endif
                                        <td>{{ $item->kode_barang}}</td>
                                        <td>{{ $item->nama_barang}}</td>
                                        <td class="text-right">{{ norupiah($item->harga_beli)}}</td>
                                        <td class="text-right">{{ norupiah($item->harga_jual)}}</td>
                                        <td class="text-center">{{ $item->jumlah}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="7">belum ada data</td>
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
        
        <div class="modal fade" id="tambah">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ url('/retur')}}" method="post">
                    @csrf
                    <input type="hidden" name="distribusi_id" value="{{ $distribusi->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Retur</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <section class="p-3">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Kode Retur</label>
                            <input type="text" name="kode_retur" id="kode_retur" value="{{ DbCikara::kodeRetur() }}" class="form-control col-md-8" readonly>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-4">Tanggal Retur</label>
                            <input type="date" name="tgl_retur" id="tgl_retur" value="{{ old('tgl_retur') }}" class="form-control col-md-8">
                        </div>
                    </section>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> BUAT RETUR</button>
                </div>
            </form>
            </div>
            </div>
        </div>
        <div class="modal fade" id="ubah">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ route('distribusi.update','test')}}" method="post">
                    @csrf
                    @method('patch')
                <div class="modal-header">
                <h4 class="modal-title">Edit Data Barang List Distribusi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" name="s" value="editbarang">
                    <input type="hidden" name="id" value="{{ $distribusi->id }}">
                    <input type="hidden" name="kode_barang" id="kode_barang">
                    <input type="hidden" name="nama_barang" id="nama_barang">
                    <section class="p-3">
                        <div class="form-group row">
                            <label for="" class="col-md-4">Harga Beli</label>
                            <input type="text" name="harga_beli" id="rupiah" class="form-control col-md-8" required>
                        </div>
                       <div class="form-group row">
                            <label for="" class="col-md-4">Harga Jual</label>
                            <input type="text" name="harga_jual" id="rupiah1" class="form-control col-md-8" required>
                        </div>
                       <div class="form-group row">
                            <label for="" class="col-md-4">Jumlah Pembelian</label>
                            <input type="number" name="jumlah" id="jumlah" min="1" class="form-control col-md-8" required>
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
        <div class="modal fade" id="distribusikan">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('distribusi.update','test')}}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="s" value="distribusikan">
                    <input type="hidden" name="id" value="{{ $distribusi->id }}">
                    <input type="hidden" name="status_stok" value="selesai">
                <div class="modal-header">
                    <h4 class="modal-title">PEMBERITAHUAN DISTRIBUSI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <section class="p-3">
                       <div class="callout callout-info text-justify">
                        Barang yang sudah didistibusikan tidak dapat dirubah kembali. Cek kembali kesesuaian dengan faktur/nota pembelian !
                       </div>
                    </section>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-truck"></i> DISTRIBUSIKAN SEKARANG</button>
                </div>
            </form>
            </div>
            </div>
        </div>
        <div class="modal fade" id="bayar">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('distribusi.update','test')}}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="s" value="pembayaran">
                    <input type="hidden" name="pelunasan" value="lunas">
                    <input type="hidden" name="id" value="{{ $distribusi->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">Pembayaran Distribusi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <section class="p-3">
                        <div class="form-group row">
                            <label for="" class="col-md-6">Tanggal Pelunasan {!! ireq() !!}</label>
                            <input type="date" name="tgl_pelunasan" id="tgl_pelunasan" class="form-control col-md-6" required>
                        </div>
                    </section>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-money-bill-wave-alt"></i> LUNASI SEKARANG</button>
                </div>
            </form>
            </div>
            </div>
        </div>
    </x-slot>
    <x-slot name="kodejs">
        <script>
            $('#ubah').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var jumlah = button.data('jumlah')
                var harga_beli = button.data('harga_beli')
                var harga_jual = button.data('harga_jual')
                var kode_barang = button.data('kode_barang')
                var nama_barang = button.data('nama_barang')
        
                var modal = $(this)
        
                modal.find('.modal-body #jumlah').val(jumlah);
                modal.find('.modal-body #rupiah').val(harga_beli);
                modal.find('.modal-body #rupiah1').val(harga_jual);
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
    </x-slot>
</x-admin-layout>