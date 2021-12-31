<?php

namespace App\Http\Controllers;

use App\Helpers\Cikara\DbCikara;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $menu = 'beranda';
        $user   = Auth::user();
        switch ($user->level) {
            case 'superadmin':
                return view('superadmin.dashboard', compact('menu'));
                break;
            case 'client':
                return view('client.dashboard', compact('menu'));
                break;
            case 'cabang':
                return view('cabang.dashboard', compact('menu'));
                break;
            case 'gudang':
                return view('gudang.dashboard', compact('menu'));
                break;
            case 'kasir':
                $cabang                 = Userakses::where('user_id',$user->id)->first();
                $totaltransaksi         = Transaksi::where('user_id',$user->id)->count();
                $totaltransaksihariini  = Transaksi::where('user_id',$user->id)->whereDate('created_at',tgl_sekarang())->count();
                $totalbarang            = Barang::where('cabang_id',$cabang->cabang_id)->count();
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
