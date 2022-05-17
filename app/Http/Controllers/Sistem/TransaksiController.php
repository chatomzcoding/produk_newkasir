<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Laporan;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
        $menu       = 'transaksi';
        $user       = Auth::user();
        $sesi = (isset($_GET['s'])) ? $_GET['s'] : 'index' ;
        $tanggal    = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang() ;
        switch ($sesi) {
            case 'pelayanan':

                break;
            
            default:
                switch ($user->level) {
                    case 'kasir':
                        $transaksi  = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->orderby('id','DESC')->get();
                        // cek status proses transaksi
                        // jika ada transaksi proses dan belum ada keranjang maka transaksi dilanjutkan
                        $cektransaksi  = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->where('status_transaksi','proses')->where('keranjang',NULL)->first();
                        $total          = Transaksi::where('user_id',$user->id)->count();
                        $totalhariini   = Transaksi::where('user_id',$user->id)->whereDate('created_at',tgl_sekarang())->count();
                        break;
                    case 'gudang':
                        $transaksi      = Transaksi::whereDate('transaksi.created_at',$tanggal)->get();
                        $total          = Transaksi::count();
                        $totalhariini   = Transaksi::whereDate('transaksi.created_at',tgl_sekarang())->count();
                        $cektransaksi = FALSE;
                        break;
                    default:
                        # code...
                        break;
                }
                $statistik  = [
                    'total' => $total,
                    'totalhariini' => $totalhariini,
                ];
                $laporan    = Laporan::where('tgl_laporan',$tanggal)->first();
                return view('sistem.transaksi.index', compact('menu','transaksi','user','tanggal','statistik','cektransaksi','laporan'));
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
                $transaksi  = Transaksi::find($request->transaksi_id);
                $barang     = Barang::where('kode_barcode',$request->barang)->first();
                if (!$barang) {
                    $barang     = Barang::where('nama_barang',$request->barang)->first();
                }

                if ($barang) {
                    if ($barang->stok > 0) {
                        $keranjang = json_decode($transaksi->keranjang,TRUE);
                        // cek jika barang sudah ada dikeranjang, maka ditambahkan 1
                        if (isset($keranjang[$barang->kode_barang]['jumlah'])) {
                            $stokkeranjang  = $keranjang[$barang->kode_barang]['jumlah'];
                            $jumlah     = $stokkeranjang + 1;
                            $keranjang[$barang->kode_barang]['jumlah'] = $jumlah;
                        } else {
                            $keranjang[$barang->kode_barang] = [
                                'kode_barang' => $barang->kode_barang,
                                'nama_barang' => $barang->nama_barang,
                                'jumlah' => 1,
                                'harga_beli' => $barang->harga_beli,
                                'harga_jual' => $barang->harga_jual,
                            ];
                            if (!is_null($transaksi->keranjang)) {
                                $dkeranjang     = json_decode($transaksi->keranjang,TRUE);
                                $keranjang      = array_merge($dkeranjang,$keranjang);
                            }
                        }
                        
                        Transaksi::where('id',$transaksi->id)->update([
                            'keranjang' => json_encode($keranjang)
                        ]);
                        
                        // kurangi stok barang dengan jumlah barang
                        $stok   = $barang->stok - 1;
                        Barang::where('id',$barang->id)->update([
                            'stok' => $stok
                        ]);
                        return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success','<strong>'.ucwords($barang->nama_barang).'</strong> berhasil ditambahkan  ke keranjang');
                    } else {
                        return redirect('transaksi/'.Crypt::encryptString($transaksi->id).'?s=caribarang')->with('danger',"<strong>".ucwords($barang->nama_barang)."</strong> stok tidak ada!   ");
                    }
                    
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
        $transaksi  = Transaksi::find(Crypt::decryptString($transaksi));
        $user       = Auth::user();
        $client     = Client::first();
        switch ($transaksi->status_transaksi) {
            case 'proses':
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'index' ;
                $data = [
                    'totalpembayaran' => totalpembayaran($transaksi->keranjang)
                ];

                $keranjang  = json_decode($transaksi->keranjang,TRUE);

                return view('sistem.transaksi.proses', compact('transaksi','s','user','data','client'));
                break;
            case 'retur':
                $s = (isset($_GET['s'])) ? $_GET['s'] : 'index' ;
                $totalpembayaranselesai     = totalpembayaran($transaksi->keranjang);
                $totalpembayaran            = $totalpembayaranselesai - $transaksi->uang_pembeli;
                $sisapembayaran             = $transaksi->uang_pembeli - $totalpembayaranselesai;
                $data = [
                    'totalpembayaranselesai' => $totalpembayaranselesai,
                    'totalpembayaran' => $totalpembayaran,
                    'sisapembayaran' => $sisapembayaran,
                ];
                return view('sistem.transaksi.proses', compact('transaksi','s','user','data','client'));
                break;
            case 'selesai':
                $invoice = datainvoice($transaksi->keranjang,$transaksi->uang_pembeli);
                $userakses  = Userakses::where('user_id',$user->id)->first();
                return view('sistem.transaksi.invoice', compact('transaksi','user','invoice','userakses','client'));
                break;
            default:
                # code...
                break;
        }
    }

    public function loadbarang()
    {
        $nama   = $_GET["query"];
        $result = Barang::where('kode_barcode',$nama)->first();
        if ($result) {
            $output['suggestions'][] = [
                'value' => $result->nama_barang,
                'nama'  => $result->nama_barang
            ];
        } else {
            $result = Barang::where('nama_barang','like','%'.$nama.'%')->get();
    
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
                    'value' => 'cari barang',
                    'nama'  => 'cari barang'
                ];
            }
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
                $jumlah         = $keranjang->$kode_barang->jumlah;
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
                // kembalikan barang ke stok barang
                $barang     = Barang::where('kode_barang',$kode_barang)->first();
                if ($barang) {
                    $stok   = $barang->stok + $jumlah;
                    Barang::where('kode_barang',$kode_barang)->update([
                        'stok' => $stok
                    ]);
                }
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('danger',$nama_barang.' berhasil dihapus dari keranjang!');
                break;
            case 'bayar':
                if (!is_null($request->nominalmanual)) {
                    $uangpembeli = $request->nominalmanual; 
                } else {
                    $uangpembeli = $request->nominal;
                }

                if ($transaksi->status_transaksi == 'retur') {
                    $uangpembeli = $uangpembeli + $transaksi->uang_pembeli;
                }
                
                Transaksi::where('id',$transaksi->id)->update([
                    'uang_pembeli' => $uangpembeli,
                    'status_transaksi' => 'selesai'
                ]);

                // kurangi barang dari stok sesuai dengan jumlah barang pembelian
                // foreach (json_decode($transaksi->keranjang) as $item) {
                //     $barang     = Barang::where('kode_barang',$item->kode_barang)->first();
                //     if ($barang) {
                //         $stok   = $barang->stok - $item->jumlah;
                //         Barang::where('id',$barang->id)->update([
                //             'stok' => $stok
                //         ]);
                //     }
                // }
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id));
                break;
            case 'tambahjumlahbarang':
                $keranjang          = json_decode($transaksi->keranjang,TRUE);
                $jumlahkeranjang    = $keranjang[$request->kode_barang]['jumlah'];
                $keranjang[$request->kode_barang]['jumlah'] = $request->jumlah;
                Transaksi::where('id',$transaksi->id)->update([
                    'keranjang' => json_encode($keranjang)
                ]);
                // ubah stok barang
                $barang     = Barang::where('kode_barang',$request->kode_barang)->first();
                if ($barang) {
                    $stok   = $barang->stok - ($request->jumlah - $jumlahkeranjang);
                    Barang::where('id',$barang->id)->update([
                        'stok' => $stok
                    ]);
                }
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success','jumlah barang '.$request->nama_barang.' telah ditambahkan!');
                break;
            case 'retur':
                // retur mengubah status dan uang pembeli dengan total pembayaran
                Transaksi::where('id',$transaksi->id)->update([
                    'status_transaksi' => $request->status_transaksi,
                    'uang_pembeli' => $request->uang_pembeli,
                ]);
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id));

                break;
            case 'batalretur':
                // ubah status tanpa ada pengurangan apapun
                Transaksi::where('id',$transaksi->id)->update([
                    'status_transaksi' => $request->status_transaksi,
                ]);
                return redirect('transaksi/'.Crypt::encryptString($transaksi->id))->with('success','Retur Dibatalkan!');

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
        if (!is_null($transaksi->keranjang)) {
            // jika transaksi dihapus, maka stok dikembalikan seperti semula
            foreach (json_decode($transaksi->keranjang) as $item) {
                $barang     = Barang::where('kode_barang',$item->kode_barang)->first();
                if ($barang) {
                    $stok   = $barang->stok + $item->jumlah;
                    Barang::where('id',$barang->id)->update([
                        'stok' => $stok
                    ]);
                }
            }
        }
        $transaksi->delete();

        return redirect('transaksi')->with('dd','Transaksi');
    }
}
