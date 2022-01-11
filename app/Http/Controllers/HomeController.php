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
            case 'superadmin':
                $statistik  = [
                    'totalclient' => Client::count(),
                    'totalcabang' => Cabang::count(),
                    'totaluser' => User::count(),
                    'totalbarang' => Barang::count()
                ];
                return view('superadmin.dashboard', compact('menu','statistik'));
                break;
            case 'client':
                $client     = Client::where('user_id',$user->id)->first();
                $statistik = [
                    'totalcabang' => Cabang::where('client_id',$client->id)->count(),
                    'totalkaryawan' => DB::table('user_akses')
                                        ->join('cabang','user_akses.cabang_id','=','cabang.id')
                                        ->where('cabang.client_id',$client->id)
                                        ->count(),
                ];
                return view('client.dashboard', compact('menu','statistik'));
                break;
            case 'cabang':
                $cabang     = Cabang::where('user_id',$user->id)->first();
                $statistik  = [
                    'totalkaryawan' => Userakses::where('cabang_id',$cabang->id)->count(),
                    'totalsupplier' => Cabang::totalsupplier($cabang->id),
                    'totalbarang' => Barang::where('cabang_id',$cabang->id)->count()
                ];
                return view('cabang.dashboard', compact('menu','statistik'));
                break;
            case 'gudang':
                $cabang         = Userakses::where('user_id',$user->id)->first();
                $totalbarang    = Barang::where('cabang_id',$cabang->cabang_id)->count();
                $totaldistribusi= Distribusi::where('cabang_id',$cabang->cabanag_id)->count();
                $totalsupplier  = Cabang::totalsupplier($cabang->cabang_id);
                $totalbarangstokkosong    = Barang::where('cabang_id',$cabang->cabang_id)->where('stok','<=',0)->count();
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
