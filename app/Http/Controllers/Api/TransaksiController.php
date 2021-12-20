<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (cektoken($_GET['token'])) {
            switch ($_GET['sesi']) {
                case 'client':
                    $result = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->where('user_akses.client_id',$_GET['id'])
                                ->select('transaksi.*')
                                ->get();
                    break;
                case 'kasir':
                    $result = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->where('user_akses.user_id',$_GET['id'])
                                ->select('transaksi.*')
                                ->get();
                    break;
                case 'filter':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $result = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.*')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->get();
                            break;
                        case 'bulanan':
                            $result = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.*')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        case 'tahunan':
                            $result = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.*')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    break;
                case 'totalpenjualan':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.keranjang','<>',NULL)
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->get();
                                
                            break;
                        case 'bulanan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        case 'tahunan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    $total  = 0;
                    foreach ($data as $item) {
                        $totalsub = 0;
                        $keranjang = json_decode($item->keranjang);
                        foreach ($keranjang as $key) {
                            $totalsub = $totalsub + $key->harga_jual;
                        }
                        $total = $total + $totalsub;
                    }
                    $result     = [
                        'totalpenjualan' => $total
                    ];
                    break;
                case 'totalitem':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.keranjang','<>',NULL)
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->get();
                                
                            break;
                        case 'bulanan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        case 'tahunan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    $total  = 0;
                    foreach ($data as $item) {
                        $totalsub = 0;
                        $keranjang = json_decode($item->keranjang);
                        foreach ($keranjang as $key) {
                            $totalsub = $totalsub + $key->jumlah;
                        }
                        $total = $total + $totalsub;
                    }
                    $result     = [
                        'totalitem' => $total
                    ];
                    break;
                case 'totallaba':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.keranjang','<>',NULL)
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->get();
                                
                            break;
                        case 'bulanan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        case 'tahunan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    $total  = 0;
                    foreach ($data as $item) {
                        $totalsub = 0;
                        $keranjang = json_decode($item->keranjang);
                        foreach ($keranjang as $key) {
                            $laba   = $key->harga_jual - $key->harga_beli;
                            $totalsub = $totalsub + $laba;
                        }
                        $total = $total + $totalsub;
                    }
                    $result     = [
                        'totallaba' => $total
                    ];
                    break;
                case 'totalproduk':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.keranjang','<>',NULL)
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->get();
                                
                            break;
                        case 'bulanan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        case 'tahunan':
                            $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->get();
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    $total  = 0;
                    foreach ($data as $item) {
                        $produk = NULL;
                        $keranjang = json_decode($item->keranjang);
                        foreach ($keranjang as $key) {
                            $produk[] = $key->nama_barang.' - '.$key->jumlah;
                        }
                    }
                    $result     = [
                        'totalproduk' => $produk
                    ];
                    break;
                case 'totalorderan':
                    switch ($_GET['status']) {
                        case 'tanggal':
                            $dinein = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','dine in')
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->count();
                            $takaway = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','take away')
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->count();
                            $delivery = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','delivery')
                                ->whereDate('transaksi.created_at',$_GET['tanggal'])
                                ->count();
                            $result = [
                                'dine-in' => $dinein,
                                'take-away' => $takaway,
                                'delivery' => $delivery
                            ];
                            break;
                        case 'bulanan':
                                $dinein = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','dine in')
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $takaway = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','take away')
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $delivery = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','delivery')
                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $result = [
                                'dine-in' => $dinein,
                                'take-away' => $takaway,
                                'delivery' => $delivery
                            ];
                            break;
                        case 'tahunan':
                            
                                $dinein = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','dine in')
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $takaway = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','take away')
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $delivery = DB::table('transaksi')
                                ->join('user_akses','transaksi.userakses_id','=','user_akses.id')
                                ->select('transaksi.tipe_orderan')
                                ->where('user_akses.user_id',$_GET['user_id'])
                                ->where('transaksi.tipe_orderan','delivery')
                                ->whereYear('transaksi.created_at',$_GET['tahun'])
                                ->count();
                            $result = [
                                'dine-in' => $dinein,
                                'take-away' => $takaway,
                                'delivery' => $delivery
                            ];
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                    }
                    break;
                default:
                    $result = [];
                    break;
            }
            return $result;
        } else {
            return response()->json('akses terlarang');
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
        if (cektoken($_POST['token'])) {
            if (isset($request->keranjang)) {
                $transaksi  = Transaksi::find($request->transaksi_id);
                if ($transaksi) {
                    $keranjang  = [
                        $request->kode_barang => [
                            'nama_barang' => $request->nama_barang,
                            'jumlah' => $request->jumlah,
                            'harga_beli' => $request->harga_beli,
                            'harga_jual' => $request->harga_jual,
                        ]
                    ];
                    if (!is_null($transaksi->keranjang)) {
                        $dkeranjang     = json_decode($transaksi->keranjang,TRUE);
                        $keranjang      = array_merge($keranjang,$dkeranjang);
                    }
    
                    Transaksi::where('id',$transaksi->id)->update([
                        'keranjang' => json_encode($keranjang)
                    ]);
                    # code...
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'transaksi tidak ada'
                    ]);
                }
            } else {
                $userakses = Userakses::where('user_id',$request->user_id)->first();
                if ($userakses) {
                    Transaksi::create([
                        'userakses_id' => $userakses->id,
                        'kode_transaksi' => 'TRX-'.time(),
                        'uang_pembeli' => $request->uang_pembeli,
                        'status_transaksi' => $request->status_transaksi,
                        'tipe_pembayaran' => $request->tipe_pembayaran,
                        'tipe_orderan' => $request->tipe_orderan,
                    ]);
                    $transaksi  = Transaksi::where('userakses_id',$userakses->id)->orderBy('id','DESC')->first();
                    return response()->json([
                        'success' => 1,
                        'message' => 'success',
                        'transaksi' => $transaksi
                    ]);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'akses user tidak ada'
                    ]);
                }
                
            }
            return response()->json([
                'success' => 1,
                'message' => 'success'
            ]);
        } else {
            return response()->json('akses terlarang');
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show($transaksi)
    {
        if (!cektoken($_GET['token'])) {
            return response()->json('akses terlarang');
        }
        $transaksi  = Transaksi::find($transaksi);
        if ($transaksi) {
            return $transaksi;
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'transaksi tidak ada'
            ]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($transaksi)
    {
        if (cektoken($_GET['token'])) {
            $transaksi  = Transaksi::find($transaksi);
            $transaksi->delete();
    
            return response()->json([
                'success' => 1,
                'message' => 'success'
            ]);
        } else {
            return response()->json('akses terlarang');
        }
    }
}
