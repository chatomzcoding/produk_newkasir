<x-admin-layout title="tambah barang" menu="barang">
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Data Barang</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('barang')}}">Daftar Barang</a></li>
                <li class="breadcrumb-item active">Tambah Barang</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </x-slot>
    <x-slot name="content">
        <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                        <a href="{{ url('barang') }}" class="btn btn-outline-secondary btn-sm pop-info" title="Kembali ke daftar Barang"><i class="fas fa-angle-left"></i> Kembali</a>
                  </div>
                  <div class="card-body">
                        <form action="{{ url('barang') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="cabang_id" value="{{ $akses->cabang_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Kode Barang <strong class="text-danger">*</strong></label>
                                        <input type="text" name="kode_barang" value="{{ DbCikara::kodeBarang($akses->cabang_id) }}" class="form-control col-md-8" readonly>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Nama Barang <strong class="text-danger">*</strong></label>
                                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control col-md-8" required>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Harga Beli <strong class="text-danger">*</strong></label>
                                        <input type="text" name="harga_beli" id="rupiah" value="{{ old('harga_beli') }}" class="form-control col-md-8" required>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Harga Jual <strong class="text-danger">*</strong></label>
                                        <input type="text" name="harga_jual" id="rupiah1" value="{{ old('harga_jual') }}" class="form-control col-md-8" required>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Stok Barang <strong class="text-danger">*</strong></label>
                                        <input type="number" name="stok" value="{{ old('stok') }}" min="1" class="form-control col-md-8" required>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Merk Barang</label>
                                        <input type="text" name="merk" value="{{ old('merk') }}" class="form-control col-md-8">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Status Barang <strong class="text-danger">*</strong></label>
                                        <select name="status_barang" id="" class="form-control col-md-8">
                                            <option value="toko">Milik Toko</option>
                                            <option value="simpanan">Barang Simpanan</option>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Produsen Barang</label>
                                        <select name="produsen_id" id="" class="form-control col-md-8">
                                            <option value="">-- tidak ada --</option>
                                            @foreach ($produsen as $item)
                                                <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 p-2">Kategori Barang <strong class="text-danger">*</strong></label>
                                        <div class="col-md-8 p-0">
                                            <select name="kategori_id" id="" class="form-control listdata" width="100%" required>
                                                <option value="">-- pilih satuan --</option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
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
                                                    <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
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
                                        <input type="text" name="kode_barcode" value="{{ old('kode_barcode') }}" class="form-control col-md-8">
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> SIMPAN BARANG</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </x-slot>
</x-admin-layout>