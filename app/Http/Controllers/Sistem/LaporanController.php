<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index($sesi)
    {
        $user   = Auth::user();
        switch ($sesi) {
            case 'transaksi':
                $menu   = 'laporantransaksi';
                $akses  = Userakses::where('user_id',$user->id)->first();
                $kategori = Kategori::kategori($akses->cabang_id);
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'harian' ;
                $skategori = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
                switch ($s) {
                    case 'harian':
                        $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                        $dstatistik  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereDate('transaksi.created_at',$tanggal)
                                        ->get();
                        $dfilter    = [
                            'tanggal' => $tanggal,
                            'page' => FALSE,
                            'link' => '&s='.$s.'&tangggal='.$tanggal
                        ];
                        $datatabel  = $dstatistik;
                        break;
                    case 'bulanan':
                        $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan();
                        $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                        $datatabel  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereMonth('transaksi.created_at',$bulan)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->paginate(10);
                        $dstatistik  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereMonth('transaksi.created_at',$bulan)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->get();
                        $dfilter    = [
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'page' => TRUE,
                            'link' => '&s='.$s.'&bulan='.$bulan.'&tahun='.$tahun
                        ];
                        break;
                    case 'tahunan':
                        $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                        $datatabel  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->paginate(10);
                        $dstatistik  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->get();
                        $dfilter    = [
                            'tahun' => $tahun,
                            'page' => TRUE,
                            'link' => '&s='.$s.'&tahun='.$tahun
                        ];
                        break;
                    
                    default:
                        # code...
                        break;
                }
                if ($skategori <> 'semua') {
                    $barang     = Barang::select('kode_barang')->where('cabang_id',$akses->cabang_id)->where('kategori_id',$skategori)->pluck('kode_barang')->toArray();
                    $dkategori  = Kategori::find($skategori);
                    $dkeranjang = [];
                    $total      = 0;
                    foreach ($dstatistik as $item) {
                        if (!is_null($item->keranjang)) {
                            $keranjang  = json_decode($item->keranjang,TRUE);
                            $cekdata    = FALSE;
                            foreach ($keranjang as $key) {
                                // cek barang kategori
                                if (in_array($key['kode_barang'],$barang)) {
                                    if (isset($dkeranjang[$key['kode_barang']])) {
                                        $dbaru[]      = $key;
                                        $lkeranjang     = $dkeranjang[$key['kode_barang']];
                                        $dkeranjang[$key['kode_barang']] = array_merge($lkeranjang,$dbaru);
                                        $dbaru          = NULL;
                                    } else {
                                        $dkeranjang[$key['kode_barang']] = [
                                            $key
                                        ];
                                    }
                                    $cekdata    = TRUE;
                                }
                            }
                            if ($cekdata) {
                                $total++;
                            }
                        }
                    }
                    $dstatistik = [
                        'total' => $total,
                        'data' => $dkeranjang
                    ];
                    $datatabel = $dkeranjang;
                }
                $statistik  = [
                    'data' => self::statistik($dstatistik,$skategori)
                ];
                $filter     = [
                    's' => $s,
                    'data' => $dfilter,
                    'kategori' => $skategori
                ];
                return view('sistem.laporan.transaksi', compact('menu','datatabel','filter','statistik','kategori'));
                break;
            
            default:
                # code...
                break;
        }
    }

    public static function statistik($transaksi,$skategori)
    {
        $total      = 0;
        $omzet      = 0;
        $itemterjual= 0;
        $laba       = 0;
        if ($skategori == 'semua') {
            if (count($transaksi) > 0) {
                foreach ($transaksi as $item) {
                    if (!is_null($item->keranjang)) {
                        $keranjang  = json_decode($item->keranjang);
                        foreach ($keranjang as $key) {
                            // total omzet
                            $omzet  = $omzet + ($key->harga_jual * $key->jumlah);
                            // total item terjual
                            $itemterjual = $itemterjual + $key->jumlah;
                            // total laba
                            $laba   = $laba + (($key->harga_jual - $key->harga_beli) * $key->jumlah);
                        }
                    }
                }
                $total  = count($transaksi);
            }
        } else {
            foreach ($transaksi['data'] as $item) {
                foreach ($item as $key) {
                      // total omzet
                      $omzet  = $omzet + ($key['harga_jual'] * $key['jumlah']);
                      // total item terjual
                      $itemterjual = $itemterjual + $key['jumlah'];
                      // total laba
                      $laba   = $laba + (($key['harga_jual'] - $key['harga_beli']) * $key['jumlah']);
                }
            }
            $total  = $transaksi['total'];
        }
        $statistik = [
            'total' => $total,
            'omzet' => $omzet,
            'itemterjual' => $itemterjual,
            'laba' => $laba,
        ];
        return $statistik;
    }
}
