<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
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
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'harian' ;
                switch ($_GET['s']) {
                    case 'harian':
                        $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                        $transaksi  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->select('transaksi.*')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereDate('transaksi.created_at',$tanggal)
                                        ->get();
                        $dfilter    = [
                            'tanggal' => $tanggal
                        ];
                        break;
                    case 'bulanan':
                        $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan();
                        $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                        $transaksi  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereMonth('transaksi.created_at',$bulan)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->get();
                        $dfilter    = [
                            'bulan' => $bulan,
                            'tahun' => $tahun
                        ];
                        break;
                    case 'tahunan':
                        $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                        $transaksi  = DB::table('transaksi')
                                        ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                        ->where('user_akses.cabang_id',$akses->cabang_id)
                                        ->whereYear('transaksi.created_at',$tahun)
                                        ->get();
                        $dfilter    = [
                            'tahun' => $tahun
                        ];
                        break;
                    
                    default:
                        # code...
                        break;
                }
                $statistik  = [
                    'data' => self::statistik($transaksi)
                ];
                $filter     = [
                    's' => $s,
                    'data' => $dfilter
                ];
                return view('sistem.laporan.transaksi', compact('menu','transaksi','filter','statistik'));
                break;
            
            default:
                # code...
                break;
        }
    }

    public static function statistik($transaksi)
    {
        $total      = 0;
        $omzet      = 0;
        $itemterjual= 0;
        $laba       = 0;
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
        $statistik = [
            'total' => $total,
            'omzet' => $omzet,
            'itemterjual' => $itemterjual,
            'laba' => $laba,
        ];
        return $statistik;
    }
}
