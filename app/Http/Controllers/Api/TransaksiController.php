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
                    # code...
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
