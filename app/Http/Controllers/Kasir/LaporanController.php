<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   = Auth::user();
        switch ($user->level) {
            case 'kasir':
                $menu   = 'eod';
                $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan() ;
                $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun() ;
                $laporan    = Laporan::where('user_id',$user->id)->whereMonth('created_at',$bulan)->whereYear('created_at',$tahun)->orderBy('id','DESC')->get();
                $filter     = [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ];
                $statistik  = [
                    'total' => Laporan::where('user_id',$user->id)->count(),
                    'totalbulanini' => count($laporan)
                ];
                return view('kasir.eod', compact('menu','laporan','filter','statistik'));
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $omzet      = 0;
        $itemterjual= 0;
        $laba       = 0;
        
        $transaksi  = Transaksi::where('user_id',$request->user_id)->whereDate('created_at',$request->tgl_laporan)->where('keranjang','<>',NULL)->get();
        $total  = count($transaksi);
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
        Laporan::create([
            'user_id' => $request->user_id,
            'tgl_laporan' => $request->tgl_laporan,
            'modal' => cikararesetrupiah($request->modal),
            'pengambilan' => cikararesetrupiah($request->pengambilan),
            'total_transaksi' => $total,
            'total_item' => $itemterjual,
            'total_penjualan' => $omzet,
            'laba' => $laba,
        ]);
       return back()->with('success','Laporan tanggal '.$request->tgl_laporan.' sudah ditutup');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function show(Laporan $laporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function edit(Laporan $laporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laporan $laporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laporan $laporan)
    {
        //
    }
}
