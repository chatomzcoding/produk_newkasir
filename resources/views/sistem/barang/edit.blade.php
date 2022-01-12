@extends('layouts.admin')

@section('title')
    Data Barang
@endsection

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('barang')}}">Daftar Barang</a></li>
            <li class="breadcrumb-item active">Edit Barang</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('container')
  
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                {{-- <h3 class="card-title">Daftar Unit</h3> --}}
                    <a href="{{ url('barang/'.Crypt::encryptString($barang->id)) }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali ke detail Barang"><i class="fas fa-angle-left"></i> Kembali</a>
              </div>
              <div class="card-body">
                  @include('sistem.notifikasi')
                    <form action="{{ url('barang/'.$barang->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Kode Barang <strong class="text-danger">*</strong></label>
                                    <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" class="form-control col-md-8" readonly>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Nama Barang <strong class="text-danger">*</strong></label>
                                    <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="form-control col-md-8" required>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Harga Beli <strong class="text-danger">*</strong></label>
                                    <input type="number" name="harga_beli" value="{{ $barang->harga_beli }}" step="any" class="form-control col-md-8" required>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Harga Jual <strong class="text-danger">*</strong></label>
                                    <input type="number" name="harga_jual" value="{{ $barang->harga_jual }}" step="any" class="form-control col-md-8" required>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Stok Barang <strong class="text-danger">*</strong></label>
                                    <input type="number" name="stok" value="{{ $barang->stok }}" min="1" class="form-control col-md-8" required>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Merk Barang</label>
                                    <input type="text" name="merk" value="{{ $barang->merk }}" class="form-control col-md-8">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Status Barang <strong class="text-danger">*</strong></label>
                                    <select name="status_barang" id="" class="form-control col-md-8">
                                        <option value="toko" @if ($barang->status_barang == 'toko')
                                            selected
                                        @endif>Milik Toko</option>
                                        <option value="simpanan" @if ($barang->status_barang == 'simpanan')
                                            selected
                                        @endif>Barang Simpanan</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Produsen Barang</label>
                                    <div class="col-md-8 p-0">
                                        <select name="produsen_id" id="" class="form-control listdata" width="100%">
                                            <option value="">-- tidak ada --</option>
                                            @foreach ($produsen as $item)
                                                <option value="{{ $item->id }}" @if ($item->id == $barang->produsen_id)
                                                    selected
                                                @endif>{{ strtoupper($item->nama) }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Kategori Barang <strong class="text-danger">*</strong></label>
                                    <div class="col-md-8 p-0">
                                        <select name="kategori_id" id="" class="form-control listdata" width="100%" required>
                                            <option value="">-- pilih satuan --</option>
                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id }}" @if ($item->id == $barang->kategori_id)
                                                    selected
                                                @endif>{{ strtoupper($item->nama) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Satuan Barang <strong class="text-danger">*</strong></label>
                                    <div class="col-md-8 p-0">
                                        <select name="satuan_id" id="" class="form-control listdata" width="100%" required>
                                            <option value="">-- pilih satuan --</option>
                                            @foreach ($satuan as $item)
                                                <option value="{{ $item->id }}" @if ($item->id == $barang->satuan_id)
                                                    selected
                                                @endif>{{ strtoupper($item->nama) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Gambar Barang</label>
                                    <input type="file" name="gambar" class="form-control col-md-8">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-md-4 p-2">Kode Barcode</label>
                                    <input type="text" name="kode_barcode" value="{{ $barang->kode_barcode }}" class="form-control col-md-8">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> SIMPAN PERUBAHAN</button>
                                </div>
                            </div>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
    </div>


    @endsection

