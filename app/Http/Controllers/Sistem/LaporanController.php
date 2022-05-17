<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Client;
use App\Models\Kategori;
use App\Models\Keuangan;
use App\Models\Laporan;
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
        $akses  = Userakses::where('user_id',$user->id)->first();
        switch ($sesi) {
            case 'transaksi':
                $kategori = Kategori::kategori();
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'harian' ;
                $skategori = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
                switch ($s) {
                    case 'harian':
                        $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                        $dstatistik  = Transaksi::whereDate('transaksi.created_at',$tanggal)->get();
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
                        $datatabel  = Transaksi::whereMonth('transaksi.created_at',$bulan)->whereYear('transaksi.created_at',$tahun)->paginate(10);
                        $dstatistik  = Transaksi::whereMonth('transaksi.created_at',$bulan)
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
                        $datatabel  = Transaksi::whereYear('transaksi.created_at',$tahun)
                                        ->paginate(10);
                        $dstatistik  = Transaksi::whereYear('transaksi.created_at',$tahun)
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
                    $barang     = Barang::select('kode_barang')->where('kategori_id',$skategori)->pluck('kode_barang')->toArray();
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
                return view('sistem.laporan.transaksi', compact('datatabel','filter','statistik','kategori'));
                break;
            
            case 'eod':
                $user_id  = (isset($_GET['user_id'])) ? $_GET['user_id'] : 'semua' ;
                $sesi  = (isset($_GET['sesi'])) ? $_GET['sesi'] : 'semua' ;
                $tanggal  = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang() ;
                $bulan  = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan() ;
                $tahun  = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun() ;
                switch ($sesi) {
                    case 'harian':
                        if ($user_id == 'semua') {
                            $eod    = Laporan::whereDate('laporan.tgl_laporan',$tanggal)
                                        ->orderBy('laporan.tgl_laporan','DESC')
                                        ->get();
                        } else {
                            $eod    = Laporan::where('user_id',$user_id)->whereDate('tgl_laporan',$tanggal)->orderBy('tgl_laporan','DESC')->get();
                        }
                        break;
                    case 'bulanan':
                        if ($user_id == 'semua') {
                            $eod    = Laporan::whereMonth('laporan.tgl_laporan',$bulan)
                                        ->whereyear('laporan.tgl_laporan',$tahun)
                                        ->orderBy('laporan.tgl_laporan','DESC')
                                        ->get();
                        } else {
                            $eod    = Laporan::where('user_id',$user_id)->whereMonth('tgl_laporan',$bulan)->whereyear('tgl_laporan',$tahun)->orderBy('tgl_laporan','DESC')->get();
                        }
                        break;
                    case 'tahunan':
                        if ($user_id == 'semua') {
                            $eod    = Laporan::whereyear('laporan.tgl_laporan',$tahun)
                                        ->orderBy('laporan.tgl_laporan','DESC')
                                        ->get();
                        } else {
                            $eod    = Laporan::where('user_id',$user_id)->whereyear('tgl_laporan',$tahun)->orderBy('tgl_laporan','DESC')->get();
                        }
                        break;
                    
                    default:
                        if ($user_id == 'semua') {
                            $eod    = Laporan::orderBy('laporan.tgl_laporan','DESC')->get();
                        } else {
                            $eod    = Laporan::where('user_id',$user_id)->orderBy('tgl_laporan','DESC')->get();
                        }
                        break;
                }
                $user   = DB::table('users')
                            ->where('users.level','kasir')
                            ->select('users.*')
                            ->orderBy('users.name','ASC')
                            ->get();
                $statistik  = self::statistikeod($eod);
                $info       = 'belum ada info';
                $data   = [
                    'eod' => $eod,
                    'user_id' => $user_id,
                    'user' => $user,
                    'sesi' => $sesi,
                    'info' => $info,
                    'statistik' => [
                        'total' => count($eod),
                        'totalitem' => $statistik['totalitem'],
                        'totaltransaksi' => $statistik['totaltransaksi'],
                        'totalpenjualan' => $statistik['totalpenjualan'],
                    ],
                    'waktu' => [
                        'tanggal' => $tanggal,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ]
                ];
                return view('sistem.laporan.eod', compact('data'));
                break;
            case 'keuangan':
                $client     = Client::first();
                $bulan      = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan() ;
                $tahun      = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun() ;
                $keuangan = Keuangan::where('bulan',$bulan)->where('tahun',$tahun)->first();
                $penjualan    = Laporan::whereMonth('tgl_laporan',$bulan)->whereYear('tgl_laporan',$tahun)->sum('total_penjualan');
                $info   = bulan_indo($bulan).' '.$tahun;
                $data   = [
                    'keuangan' => $keuangan,
                    'akses' => $akses,
                    'penjualan' => $penjualan,
                    'waktu' => [
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'info' => $info,
                    ],
                    'statistik' => [
                        'total' => Keuangan::count(),
                    ]
                ];

                return view('sistem.laporan.keuangan', compact('data','client'));
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

    public static function statistikeod($eod)
    {
        $totalitem      = 0;
        $totaltransaksi = 0;
        $totalpenjualan     = 0;
        if (count($eod) > 0) {
            foreach ($eod as $key) {
                $totalitem  = $totalitem + $key->total_item;
                $totaltransaksi  = $totaltransaksi + $key->total_transaksi;
                $totalpenjualan  = $totalpenjualan + $key->total_penjualan;
            }
        }
        $result = [
            'totalitem' => $totalitem,
            'totaltransaksi' => $totaltransaksi,
            'totalpenjualan' => $totalpenjualan,
        ];
        return $result;
    }
}
