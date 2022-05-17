<?php

namespace App\Http\Controllers;

use App\Helpers\Cikara\DbCikara;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Distribusi;
use App\Models\Retur;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $menu = 'beranda';
        $user   = Auth::user();
        switch ($user->level) {
            case 'gudang':
                $cabang         = Userakses::where('user_id',$user->id)->first();
                $totalbarang    = Barang::count();
                $totaldistribusi= Distribusi::count();
                $totalsupplier  = Supplier::count();
                $totalbarangstokkosong    = Barang::where('stok','<=',0)->count();
                $statistik = [
                    'totalbarang' => $totalbarang,
                    'totaldistribusi' => $totaldistribusi,
                    'totalsupplier' => $totalsupplier,
                    'totalbarangstokkosong' => $totalbarangstokkosong,
                ];
                return view('gudang.dashboard', compact('menu','statistik'));
                break;

            case 'kasir':
                $cabang                 = Userakses::where('user_id',$user->id)->first();
                $totaltransaksi         = Transaksi::where('user_id',$user->id)->count();
                $totaltransaksihariini  = Transaksi::where('user_id',$user->id)->whereDate('created_at',tgl_sekarang())->count();
                $totalbarang            = Barang::count();
                $totalomzethariini      = '120000';
                $statistik = [
                    'totaltransaksi' => $totaltransaksi,
                    'totalbarang' => $totalbarang,
                    'totaltransaksihariini' => $totaltransaksihariini,
                    'totalomzethariini' => rupiah(DbCikara::omzetByUser('hariini',['user_id' => $user->id])),
                ];
                $chart      = [
                    'omzet-harian' => DbCikara::omzetByUser('chartharianperbulan',['user_id' => $user->id])
                ];

                return view('kasir.dashboard', compact('menu','statistik','chart'));
                break;
            default:
                # code...
                break;
        }
    }
}
