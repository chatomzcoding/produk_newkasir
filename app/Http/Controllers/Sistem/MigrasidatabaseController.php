<?php

namespace App\Http\Controllers\Sistem;

use App\Helpers\Cikara\DbCikara;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MigrasidatabaseController extends Controller
{
    public function index()
    {
        $menu       = 'migrasi';
        $migrasi    = ['user','barang','supplier','distribusi','transaksi','laporan'];
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
                                    $barang[$kodebarang] = [
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
            case 'transaksi':
                $transaksi  = DB::table('transaksi')
                                ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                ->where('user_akses.cabang_id',$cabang->cabang_id)
                                ->count();
                if (isset($_GET['s']) AND $transaksi == 0) {
                    $data   = DB::table('kasir_transaksi')->where('status_transaksi','selesai')->get();
                    foreach ($data as $key) {
                        $kode               = explode('-',$key->kode_transaksi);
                        $userlama           = DB::table('kasir_user')->select('email')->where('id',$key->user_id)->first();
                        $userbaru           = User::select('id')->where('email',$userlama->email)->first();
                        $kode_transaksi   = 'TRX'.$userbaru->id.'.'.substr($kode[2],2,6).'.'.$kode[3];

                        // data keranjang
                        $dkeranjang         = DB::table('kasir_keranjang')->where('kasirtransaksi_id',$key->id)->get();
                        $keranjang  = [];
                        foreach ($dkeranjang as $k) {
                            $no    = substr($k->kode_barang,4,4);
                            $kodebarang     = 'CK'.$cabang->cabang_id.'.'.$no;
                            if ($k->hargabeli_barang == '') {
                                $dbarang    = Barang::select('harga_beli')->where('kode_barang',$kodebarang)->first();
                                if ($dbarang) {
                                    $harga_beli     = $dbarang->harga_beli;
                                } else {
                                    $harga_beli     = NULL;
                                }
                            } else {
                                $harga_beli     = $k->hargabeli_barang;
                            }
                            if (!is_null($harga_beli)) {
                                $keranjang[$kodebarang] = [
                                    'kode_barang' => $kodebarang,
                                    'nama_barang' => $k->nama_barang,
                                    'jumlah' => $k->jumlah,
                                    'harga_beli' => $harga_beli,
                                    'harga_jual' => $k->harga_barang,
                                ];
                            }
                        }
                        Transaksi::create([
                            'user_id' => $userbaru->id,
                            'kode_transaksi' => $kode_transaksi,
                            'status_transaksi' => $key->status_transaksi,
                            'uang_pembeli' => $key->uang_pembeli,
                            'keranjang' => json_encode($keranjang),
                            'created_at' => $k->created_at,
                            'updated_at' => $k->updated_at
                        ]);
                    }
                }
                $data   = DB::table('kasir_transaksi')->paginate(10);
                break;
            case 'user':
                $data   = DB::table('kasir_user')->where('level','kasir')->get();
                if (isset($_GET['s'])) {
                    foreach ($data as $key) {
                        User::create([
                            'name' => $key->name,
                            'email' => $key->email,
                            'level' => $key->level,
                            'password' => $key->password,
                        ]);

                        // add to user akses
                        $user   = User::where('name',$key->name)->where('email',$key->email)->first();
                        Userakses::create([
                            'cabang_id' => $cabang->cabang_id,
                            'user_id' => $user->id,
                        ]);
                    }
                }
                break;
            
            case 'laporan':
                if (isset($_GET['s'])) {
                    $data    = DB::table('kasir_laporan')
                    ->join('kasir_user','kasir_laporan.user_id','=','kasir_user.id')
                    ->join('users','kasir_user.email','=','users.email')
                    ->select('kasir_laporan.*','users.id as iduser','users.email')
                    ->orderBy('kasir_laporan.tgl_laporan','ASC')
                    ->get();
                    foreach ($data as $item) {
                        $ceklaporan = Laporan::where('user_id',$item->iduser)->where('tgl_laporan',$item->tgl_laporan)->first();
                        if (!$ceklaporan) {
                            $totalitem  = 0;
                            $transaksi  = Transaksi::select('keranjang')->where('user_id',$item->iduser)->where('keranjang','<>',NULL)->whereDate('created_at',$item->tgl_laporan)->get();
                            if (count($transaksi) > 0) {
                                foreach ($transaksi as $key) {
                                    $keranjang = json_decode($key->keranjang);
                                    foreach ($keranjang as $k) {
                                        $totalitem = $totalitem + $k->jumlah;
                                    }
                                }
                            }
                            Laporan::create([
                                'user_id' => $item->iduser,
                                'tgl_laporan' => $item->tgl_laporan,
                                'total_transaksi' => $item->total_transaksi,
                                'total_item' => $totalitem,
                                'total_penjualan' => $item->total_penjualan,
                                'modal' => $item->total_modal,
                                'pengambilan' => $item->total_pengambilan,
                                'laba' => $item->total_laba
                            ]);
                        }
                    }
                }
                $data    = DB::table('kasir_laporan')
                ->join('kasir_user','kasir_laporan.user_id','=','kasir_user.id')
                ->join('users','kasir_user.email','=','users.email')
                ->select('kasir_laporan.*','users.id as iduser','users.email')
                ->orderBy('kasir_laporan.tgl_laporan','ASC')
                ->paginate(10);
                break;
                default:
                return 'tidak ada sesi';
                break;
        }
        return view('sistem.migrasi.list', compact('menu','data','sesi'));

    }
}