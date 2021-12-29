<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Cikara\DbCikara;
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
                case 'cabang':
                    $data = DB::table('transaksi')
                                ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                ->where('user_akses.cabang_id',$_GET['id'])
                                ->select('transaksi.*')
                                ->get();
                    $result = [];
                    if (count($data) > 0) {
                        foreach ($data as $item) {
                            if (is_null($item->keranjang)) {
                                $keranjang = [];
                            } else {
                                $keranjang  = json_decode($item->keranjang);
                            }
                            $data   = [
                                'id' => $item->id,
                                'kode_transaksi' => $item->kode_transaksi,
                                'status_transaksi' => $item->status_transaksi,
                                'tipe_orderan' => $item->tipe_orderan,
                                'tipe_pembayaran' => $item->tipe_pembayaran,
                                'diskon' => $item->diskon,
                                'uang_pembeli' => $item->uang_pembeli,
                                'keranjang' => $keranjang
                            ];
                            $result[] = $data;
                        }
                    }
                    break;
                case 'kasir':
                    $data = Transaksi::where('user_id',$_GET['id'])->get();
                    $result = [];
                    if (count($data) > 0) {
                        foreach ($data as $item) {
                            if (is_null($item->keranjang)) {
                                $keranjang = [];
                            } else {
                                $keranjang  = json_decode($item->keranjang);
                            }
                            $data   = [
                                'id' => $item->id,
                                'kode_transaksi' => $item->kode_transaksi,
                                'status_transaksi' => $item->status_transaksi,
                                'tipe_orderan' => $item->tipe_orderan,
                                'tipe_pembayaran' => $item->tipe_pembayaran,
                                'uang_pembeli' => $item->uang_pembeli,
                                'diskon' => $item->diskon,
                                'keranjang' => $keranjang
                            ];
                            $result[] = $data;
                        }
                    }
                    break;
                case 'filter':
                    // total orderan
                    $tipe_order = ['dine in','take away','delivery'];
                    $totalorderan = [];
                    switch ($_GET['status']) {
                        case 'tanggal':
                            // total transaksi
                            $datatransaksi  = Transaksi::where('user_id',$_GET['user_id'])
                                                ->whereDate('created_at',$_GET['tanggal'])
                                                ->get();
                            $data           = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('keranjang','<>',NULL)
                                                ->whereDate('created_at',$_GET['tanggal'])
                                                ->get();
                           
                            for ($i=0; $i < count($tipe_order); $i++) { 
                                $total      = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('tipe_orderan',$tipe_order[$i])
                                                ->whereDate('created_at',$_GET['tanggal'])
                                                ->count();
                                $totalorderan[$tipe_order[$i]] = $total;
                            }
                            break;
                        case 'bulanan':
                            $datatransaksi  = Transaksi::where('user_id',$_GET['user_id'])
                                                ->whereMonth('created_at',$_GET['bulan'])
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->get();
                            $data           = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('keranjang','<>',NULL)
                                                ->whereMonth('created_at',$_GET['bulan'])
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->get();
                            for ($i=0; $i < count($tipe_order); $i++) { 
                                $total      = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('tipe_orderan',$tipe_order[$i])
                                                ->whereMonth('transaksi.created_at',$_GET['bulan'])
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->count();
                                $totalorderan[$tipe_order[$i]] = $total;
                            }
                            break;
                        case 'tahunan':
                            $datatransaksi  = Transaksi::where('user_id',$_GET['user_id'])
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->get();
                            $data           = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('keranjang','<>',NULL)
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->get();
                            for ($i=0; $i < count($tipe_order); $i++) { 
                                $total      = Transaksi::where('user_id',$_GET['user_id'])
                                                ->where('tipe_orderan',$tipe_order[$i])
                                                ->whereYear('created_at',$_GET['tahun'])
                                                ->count();

                                $totalorderan[$tipe_order[$i]] = $total;
                            }
                            break;
                        
                        default:
                            $result = NULL;
                            break;
                        }

                        $transaksi = [];
                        if (count($datatransaksi) > 0) {
                            foreach ($datatransaksi as $item) {
                                if (is_null($item->keranjang)) {
                                    $keranjang = [];
                                } else {
                                    $keranjang  = json_decode($item->keranjang);
                                }
                                $databarang   = [
                                    'id' => $item->id,
                                    'kode_transaksi' => $item->kode_transaksi,
                                    'status_transaksi' => $item->status_transaksi,
                                    'diskon' => $item->diskon,
                                    'tipe_orderan' => $item->tipe_orderan,
                                    'tipe_pembayaran' => $item->tipe_pembayaran,
                                    'uang_pembeli' => $item->uang_pembeli,
                                    'keranjang' => $keranjang
                                ];
                                $transaksi[] = $databarang;
                            }
                        }
                        // total penjualan
                        $totalpenjualan  = 0;
                        $totalitem  = 0;
                        $totallaba  = 0;
                        $produk = NULL;
                        foreach ($data as $item) {
                            $subpenjualan = 0;
                            $subitem = 0;
                            $sublaba = 0;
                            $keranjang = json_decode($item->keranjang);
                            if (!is_null($keranjang)) {
                                foreach ($keranjang as $key) {
                                    $subpenjualan = $subpenjualan + $key->harga_jual;
                                    $subitem = $subitem + $key->jumlah;
                                    $laba   = $key->harga_jual - $key->harga_beli;
                                    $sublaba = $sublaba + $laba;
                                    // cek apakah tidak ada barang
                                    if (isset($produk[$key->kode_barang])) {
                                        $jumlah = $key->jumlah + $produk[$key->kode_barang]['jumlah'];
                                        $produk[$key->kode_barang]['jumlah'] = $jumlah;
                                    } else {
                                        $produk[$key->kode_barang] = [
                                            'nama_barang' => $key->nama_barang,
                                            'jumlah' => $key->jumlah
                                        ];
                                    }
                                    
                                }
                            }
                            $totalpenjualan = $totalpenjualan + $subpenjualan;
                            $totalitem = $totalitem + $subitem;
                            $totallaba = $totallaba + $sublaba;
                            
                        }
                      
                       
                        $result = [
                            'transaksi' => $transaksi,
                            'totalpenjualan' => $totalpenjualan,
                            'totalitem' => $totalitem,
                            'totallaba' => $totallaba,
                            'totalproduk' => $produk,
                            'totalorderan' => $totalorderan,
                        ];
                        
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
                    $keranjang  = [];
                    $barang     = json_decode($request->barang);
                    foreach ($barang as $key) {
                        $keranjang[$key->kode_barang] = [
                            'kode_barang' => $key->kode_barang,
                            'nama_barang' => $key->nama_barang,
                            'jumlah' => $key->jumlah,
                            'harga_beli' => $key->harga_beli,
                            'harga_jual' => $key->harga_jual,
                        ];
                    }
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
                Transaksi::create([
                    'user_id' => $request->user_id,
                    'kode_transaksi' => DbCikara::kodeTransaksi($request->user_id),
                    'uang_pembeli' => $request->uang_pembeli,
                    'status_transaksi' => $request->status_transaksi,
                    'tipe_pembayaran' => $request->tipe_pembayaran,
                    'tipe_orderan' => $request->tipe_orderan,
                    'diskon' => $request->diskon,
                ]);
                $transaksi  = Transaksi::where('user_id',$request->user_id)->orderBy('id','DESC')->first();
                return response()->json([
                    'success' => 1,
                    'message' => 'success',
                    'transaksi' => $transaksi
                ]);
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
                if (is_null($transaksi->keranjang)) {
                    $keranjang = [];
                } else {
                    $keranjang  = json_decode($transaksi->keranjang);
                }
                $data   = [
                    'id' => $transaksi->id,
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'status_transaksi' => $transaksi->status_transaksi,
                    'tipe_orderan' => $transaksi->tipe_orderan,
                    'tipe_pembayaran' => $transaksi->tipe_pembayaran,
                    'uang_pembeli' => $transaksi->uang_pembeli,
                    'diskon' => $transaksi->diskon,
                    'keranjang' => $keranjang
                ];
            return $data;
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
            if (isset($_GET['user_id'])) {
                Transaksi::where('user_id',$_GET['user_id'])->delete();
            } else {
                $transaksi  = Transaksi::find($transaksi);
                $transaksi->delete();
            }
            
    
            return response()->json([
                'success' => 1,
                'message' => 'success'
            ]);
        } else {
            return response()->json('akses terlarang');
        }
    }
}
