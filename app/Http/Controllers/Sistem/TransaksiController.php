<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu       = 'transaksi';
        $user       = Auth::user();
        $sesi = (isset($_GET['s'])) ? $_GET['s'] : 'index' ;
        switch ($sesi) {
            case 'pelayanan':

                break;
            
            default:
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang() ;
                $transaksi  = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->orderby('id','DESC')->get();
                return view('sistem.transaksi.index', compact('menu','transaksi','user','tanggal'));
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
        switch ($request->sesi) {
            case 'tambah':
                Transaksi::create([
                    'user_id' => $request->user_id,
                    'kode_transaksi' => $request->kode_transaksi,
                    'status_transaksi' => $request->status_transaksi,
                    'uang_pembeli' => $request->uang_pembeli,
                ]);
                $transaksi  = Transaksi::where('user_id',$request->user_id)->latest()->first();
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id));
                break;
            case 'tambahbarang':
                $akses      = Userakses::where('user_id',Auth::user()->id)->first();
                $transaksi  = Transaksi::find($request->transaksi_id);
                if ($request->status == 'barcode') {
                    $barang     = Barang::where('cabang_id',$akses->cabang_id)->where('kode_barcode',$request->kode_barcode)->first();
                } else {
                    $barang     = Barang::where('cabang_id',$akses->cabang_id)->where('nama_barang',$request->nama_barang)->first();
                }
                
                if ($barang) {
                    $keranjang[$barang->kode_barang] = [
                        'kode_barang' => $barang->kode_barang,
                        'nama_barang' => $barang->nama_barang,
                        'jumlah' => 1,
                        'harga_beli' => $barang->harga_beli,
                        'harga_jual' => $barang->harga_jual,
                    ];
                    if (!is_null($transaksi->keranjang)) {
                        $dkeranjang     = json_decode($transaksi->keranjang,TRUE);
                        $keranjang      = array_merge($keranjang,$dkeranjang);
                    }
    
                    Transaksi::where('id',$transaksi->id)->update([
                        'keranjang' => json_encode($keranjang)
                    ]);
    
                    return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success',$barang->nama_barang. ' berhasil ditambahkan  ke keranjang');
                } else {
                    // jika tidak ada, berikan notifikasi barang tidak ada
                    return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('warning','barang tidak ada digudang!');
                }
                
                break;
            default:
                # code...
                break;
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
        $menu       = 'transaksi';
        $transaksi  = Transaksi::find(Crypt::decryptString($transaksi));
        $user       = Auth::user();
        switch ($transaksi->status_transaksi) {
            case 'proses':
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'index' ;
                $data = [
                    'totalpembayaran' => totalpembayaran($transaksi->keranjang)
                ];
                return view('sistem.transaksi.proses', compact('menu','transaksi','s','user','data'));
                break;
            
            default:
                # code...
                break;
        }
    }

    public function loadbarang()
    {
        // Deklarasi variable keyword buah.
        $nama   = $_GET["query"];
        $akses  = Userakses::where('user_id',Auth::user()->id)->first();
        $result = Barang::where('cabang_id',$akses->cabang_id)->where('nama_barang','like','%'.$nama.'%')->get();

        // Cek apakah ada yang cocok atau tidak.
        if (count($result) > 0) {
            foreach($result as $item) {
                $output['suggestions'][] = [
                    'value' => $item->nama_barang,
                    'nama'  => $item->nama_barang
                ];
            }
        // Jika tidak ada yang cocok.
        } else {
            $output['suggestions'][] = [
                'value' => '',
                'nama'  => ''
            ];

        }
        // Encode ke JSON.
        echo json_encode($output);
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
        switch($request->s) {
            case 'hapusitem':
                $kode_barang    = $request->kode_barang;
                $keranjang      = json_decode($transaksi->keranjang);
                $nama_barang    = $keranjang->$kode_barang->nama_barang;
                unset($keranjang->$kode_barang);
                // cek jika keranjang masih ada barang
                if ((array)$keranjang) {
                    Transaksi::where('id',$transaksi->id)->update([
                        'keranjang' => json_encode($keranjang)
                    ]);
                } else {
                    // jika tidak ada kasih NULL
                    Transaksi::where('id',$transaksi->id)->update([
                        'keranjang' => NULL
                    ]);
                }
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('danger',$nama_barang.' berhasil dihapus dari keranjang!');
                break;
            case 'bayarpas':
                Transaksi::where('id',$transaksi->id)->update([
                    'uang_pembeli' => $request->uang_pembeli,
                    'status_transaksi' => 'selesai'
                ]);
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success', 'transaksi telah selesai!');
                break;
            case 'tambahjumlahbarang':
                $keranjang = json_decode($transaksi->keranjang,TRUE);
                $keranjang[$request->kode_barang]['jumlah'] = $request->jumlah;
                Transaksi::where('id',$transaksi->id)->update([
                    'keranjang' => json_encode($keranjang)
                ]);
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success','jumlah barang '.$request->nama_barang.' telah ditambahkan!');
                break;
            default:

                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect('transaksi')->with('dd','Transaksi');
    }
}
