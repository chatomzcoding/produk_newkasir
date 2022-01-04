<?php

namespace App\Http\Controllers\Sistem;

use App\Helpers\Cikara\DbCikara;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MigrasidatabaseController extends Controller
{
    public function index()
    {
        $menu       = 'migrasi';
        $migrasi    = ['barang'];
        return view('sistem.migrasi.index', compact('menu','migrasi'));
    }

    public function page($sesi)
    {
        $menu       = 'migrasi';
        switch ($sesi) {
            case 'barang':
                if (isset($_GET['s'])) {
                    // simpan ke table baru
                    $cabang     = Userakses::where('user_id',Auth::user()->id)->first();
                    $data   = DB::table('kasir_barang')->get();
                    $no     = 1;
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
                        if ($no < 10 ) {
                            $urutan = '000'.$no;
                        }elseif ($no >= 10 AND $no < 100) {
                            $urutan = '00'.$no;
                        }elseif ($no >= 100 AND $no < 1000) {
                            $urutan = '0'.$no;
                        } else {
                            $urutan = $no;
                        }

                        $kodebarang = 'CK'.$cabang->cabang_id.'.'.$urutan;
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
                $data   = DB::table('kasir_barang')->paginate(20);
                break;
            
            default:
                $data       = NULL;
                break;
        }
        return view('sistem.migrasi.list', compact('menu','data','sesi'));

    }
}
