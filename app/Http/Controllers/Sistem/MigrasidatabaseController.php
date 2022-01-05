<?php

namespace App\Http\Controllers\Sistem;

use App\Helpers\Cikara\DbCikara;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MigrasidatabaseController extends Controller
{
    public function index()
    {
        $menu       = 'migrasi';
        $migrasi    = ['barang','supplier','distribusi'];
        return view('sistem.migrasi.index', compact('menu','migrasi'));
    }

    public function page($sesi)
    {
        $menu       = 'migrasi';
        $cabang     = Userakses::where('user_id',Auth::user()->id)->first();
        switch ($sesi) {
            case 'barang':
                if (isset($_GET['s'])) {
                    // simpan ke table baru
                    $data   = DB::table('kasir_barang')->get();
                    $no     = 1;
                    $cekbarang  = Barang::where('cabang_id',$cabang->cabang_id)->first();
                    if (!$cekbarang) {
                        // jika belum ada barang maka bisa di import
                        foreach ($data as $row) {
                            $satuanbarang   = strtolower($row->satuan_barang);
                            $kategoribarang   = strtolower($row->kategori);
                            $produsenbarang   = strtolower($row->produsen);
                            $satuan     = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','satuan')->where('nama',$satuanbarang)->first();
                            if ($satuan) {
                                $satuan_id = $satuan->id;
                            } else {
                                Kategori::create([
                                    'cabang_id' => $cabang->cabang_id,
                                    'label' => 'satuan',
                                    'nama' => $satuanbarang,
                                ]);
                                $satuan_id = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','satuan')->latest()->first()->id;
                            }
                            
                            $kategori     = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','kategori')->where('nama',$kategoribarang)->first();
                            if ($kategori) {
                                $kategori_id = $kategori->id;
                            } else {
                                Kategori::create([
                                    'cabang_id' => $cabang->cabang_id,
                                    'label' => 'kategori',
                                    'nama' => $kategoribarang,
                                ]);
                                $kategori_id = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','kategori')->latest()->first()->id;
                            }
                            $produsen     = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','produsen')->where('nama',$produsenbarang)->first();
                            if ($produsen) {
                                $produsen_id = $produsen->id;
                            } else {
                                Kategori::create([
                                    'cabang_id' => $cabang->cabang_id,
                                    'label' => 'produsen',
                                    'nama' => $produsenbarang,
                                ]);
                                $produsen_id = Kategori::where('cabang_id',$cabang->cabang_id)->where('label','produsen')->latest()->first()->id;
                            }
                            // kode barang
                            $nomor    = substr($row->kode_barang,4,4);
                            $kodebarang     = 'CK'.$cabang->cabang_id.'.'.$nomor;
    
                            Barang::create([
                                'cabang_id' => $cabang->cabang_id,
                                'kode_barang' => $kodebarang,
                                'nama_barang' => $row->nama_barang,
                                'kategori_id' => $kategori_id,
                                'satuan_id' => $satuan_id,
                                'harga_beli' => $row->harga_beli,
                                'harga_jual' => $row->harga_jual,
                                'stok' => $row->stok,
                                'gambar' => $row->gambar,
                                'kode_barcode' => $row->kode_barcode,
                                'merk' => $row->merk,
                                'status_barang' => $row->status_barang,
                                'produsen_id' => $produsen_id,
                            ]);
                            $no++;
                        }
                    }
                }
                $data   = DB::table('kasir_barang')->paginate(20);
                break;
            case 'supplier':
                if (isset($_GET['s'])) {
                    $ceksupplier    = Supplier::where('cabang_id',$cabang->cabang_id)->first();
                    if (!$ceksupplier) {
                        $data   = DB::table('kasir_supplier')->get();
                        foreach ($data as $key) {
                            Supplier::create([
                                'cabang_id' => $cabang->cabang_id,
                                'nama_supplier' => $key->nama_supplier,
                                'telp' => $key->telp,
                                'alamat' => $key->alamat,
                            ]);
                        }
                    }
                }
                $data   = DB::table('kasir_supplier')->paginate(20);
                break;
            case 'distribusi':
                if (isset($_GET['s'])) {
                    $cek    = Distribusi::where('cabang_id',$cabang->cabang_id)->first();
                    if (!$cek) {
                        $data   = DB::table('kasir_distribusi')
                                    ->join('kasir_supplier','kasir_distribusi.kasirsupplier_id','=','kasir_supplier.id')
                                    ->select('kasir_distribusi.*','kasir_supplier.nama_supplier','kasir_supplier.alamat')
                                    ->get();
                        foreach ($data as $key) {
                            $supplier   = Supplier::where('nama_supplier',$key->nama_supplier)->where('alamat',$key->alamat)->first();
                            if ($supplier) {
                                $nomor  = substr($key->kode_distribusi,3,4);
                                $kode_distribusi   = 'DB'.$cabang->cabang_id.'.'.$nomor;
                                if ($key->status_stok == 'sudah') {
                                    $status = 'selesai';
                                } else {
                                    $status = $key->status_stok;
                                }
                                // input barang 
                                $listbarang     = DB::table('kasir_listdistribusi')
                                                    ->join('kasir_barang','kasir_listdistribusi.kode_barang','=','kasir_barang.kode_barang')
                                                    ->select('kasir_listdistribusi.kode_barang','kasir_listdistribusi.jumlah','kasir_barang.nama_barang','kasir_barang.harga_beli','kasir_barang.harga_jual')
                                                    ->where('kasir_listdistribusi.kasirdistribusi_id',$key->id)->get();
                                    $barang     = [];
                                    foreach ($listbarang as $k) {
                                    // kode barang
                                    $no    = substr($k->kode_barang,4,4);
                                    $kodebarang     = 'CK'.$cabang->cabang_id.'.'.$no;
                                    $barang[$k->kode_barang] = [
                                        'kode_barang' => $kodebarang,
                                        'nama_barang' => $k->nama_barang,
                                        'jumlah' => $k->jumlah,
                                        'harga_beli' => $k->harga_beli,
                                        'harga_jual' => $k->harga_jual,
                                    ];
                                }
                                Distribusi::create([
                                    'kode_distribusi' => $kode_distribusi,
                                    'supplier_id' => $supplier->id,
                                    'cabang_id' => $cabang->cabang_id,
                                    'no_faktur' => $key->no_faktur,
                                    'tgl_faktur' => $key->tgl_faktur,
                                    'tgl_tempo' => $key->tgl_tempo,
                                    'pembayaran' => $key->pembayaran,
                                    'potongan' => $key->potongan,
                                    'status_stok' => $status,
                                    'barang' => json_encode($barang),
                                ]);
                            }
                        }
                    }
                }
                $data   = DB::table('kasir_distribusi')->paginate(20);
                break;
            default:
                $data       = NULL;
                break;
        }
        return view('sistem.migrasi.list', compact('menu','data','sesi'));

    }
}
