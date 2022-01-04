<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Retur;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu       = 'distribusi';
        $akses      = Userakses::where('user_id',Auth::user()->id)->first();
        $tanggal    = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : 'semua' ;
        if ($tanggal == 'semua') {
            $distribusi = Distribusi::where('cabang_id',$akses->cabang_id)->get();
        } else {
            $distribusi = Distribusi::where('cabang_id',$akses->cabang_id)->whereDate('created_at',$tanggal)->get();
        }
        $supplier   = Kategori::where('cabang_id',$akses->cabang_id)->where('label','supplier')->get();
        $statistik  = [
            'total' => Distribusi::where('cabang_id',$akses->cabang_id)->count(),
            'totalbulanini' => Distribusi::where('cabang_id',$akses->cabang_id)->whereMonth('created_at',ambil_bulan())->whereYear('created_at',ambil_tahun())->count(),
            'totalproses' => Distribusi::where('cabang_id',$akses->cabang_id)->where('status_stok','proses')->count(),
        ];
        $filter     = [
            'tanggal' => $tanggal
        ];
        return view('gudang.distribusi.index', compact('menu','distribusi','akses','supplier','statistik','filter'));
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
        switch ($request->s) {
            case 'tambahdistribusi':
                Distribusi::create([                                                                                            
                    'kode' => $request->kode,
                    'no_faktur' => $request->no_faktur,
                    'tgl_faktur' => $request->tgl_faktur,
                    'tgl_tempo' => $request->tgl_tempo,
                    'pembayaran' => $request->pembayaran,
                    'potongan' => $request->potongan,
                    'status_stok' => $request->status_stok,
                    'cabang_id' => $request->cabang_id,
                    'kategori_id' => $request->kategori_id,
                ]);
        
                $distribusi     = Distribusi::where('kode',$request->kode)->first();
                return redirect('distribusi/'.Crypt::encryptString($distribusi->id));
                break;
            case 'tambahbarang':
               
            
            default:
                return 'sesi tidak ada';
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function show($distribusi)
    {
        $menu       = 'distribusi';
        $distribusi = Distribusi::find(Crypt::decryptString($distribusi));
        $akses      = Userakses::where('user_id',Auth::user()->id)->first();
        $s = (isset($_GET['s'])) ? $_GET['s'] : 'show' ;
        switch ($s) {
            case 'tambahbarang':
                if (!empty($_GET['barcode'])) {
                    $barang = Barang::where('cabang_id',$distribusi->cabang_id)->where('kode_barcode',$_GET['barcode'])->first();
                    $info   = 'Barcode : '.$_GET['barcode'];
                } else {
                    $barang = Barang::where('cabang_id',$distribusi->cabang_id)->where('nama_barang',$_GET['nama_barang'])->first();
                    $info   = 'Nama Barang  : '.$_GET['nama_barang'];
                }

                if ($barang) {
                    $databarang     = json_decode($distribusi->barang);
                    $kodebarang     = $barang->kode_barang;
                    if (isset($databarang->$kodebarang)) {
                        return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('warning','Barang '.$barang->nama_barang.' sudah ditambahkan');
                    }
                    return view('gudang.distribusi.tambahbarang', compact('menu','distribusi','barang'));
                } else {
                    return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('danger','Pencarian barang dengan '.$info.' tidak ditemukan !');
                }
                break;
            
            default:
                // kode untuk cek apakah distribusi sudah ada retur atau tidak
                $retur  = Retur::where('distribusi_id',$distribusi->id)->first();
                return view('gudang.distribusi.show', compact('menu','distribusi','akses','retur'));
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function edit(Distribusi $distribusi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $distribusi     = Distribusi::find($request->id);
        switch ($request->s) {
            case 'editdistribusi':
                Distribusi::where('id',$request->id)->update([                                                                                            
                    'no_faktur' => $request->no_faktur,
                    'tgl_faktur' => $request->tgl_faktur,
                    'tgl_tempo' => $request->tgl_tempo,
                    'pembayaran' => $request->pembayaran,
                    'potongan' => $request->potongan,
                    'kategori_id' => $request->kategori_id,
                ]);
        
                return redirect('distribusi/'.Crypt::encryptString($request->id));
                break;

            case 'tambahbarang':
                $barangbaru[$request->kode_barang] = [
                    'kode_barang' => $request->kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'jumlah' => $request->jumlah,
                    'harga_beli' => cikararesetrupiah($request->harga_beli),
                    'harga_jual' => cikararesetrupiah($request->harga_jual),
                ];
                // cek jika barang sudah ada
                if (!is_null($distribusi->barang)) {
                    $dbarang     = json_decode($distribusi->barang,TRUE);
                    $barangbaru      = array_merge($dbarang,$barangbaru);
                }
                Distribusi::where('id',$distribusi->id)->update([
                    'barang' => json_encode($barangbaru)
                ]);
                return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('success','Barang '.$request->nama_barang.' telah ditambahkan');
                
                break;
            
            case 'editbarang':
                $dbarang    = json_decode($distribusi->barang,TRUE);
                // ubah harga dan jumlah
                $dbarang[$request->kode_barang]['harga_jual'] = cikararesetrupiah($request->harga_jual);
                $dbarang[$request->kode_barang]['harga_beli'] = cikararesetrupiah($request->harga_beli);
                $dbarang[$request->kode_barang]['jumlah'] = cikararesetrupiah($request->jumlah);

                Distribusi::where('id',$distribusi->id)->update([
                    'barang' => json_encode($dbarang)
                ]);
                return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('success','Barang '.$request->nama_barang.' telah diperbaharui');

                break;
            
            case 'hapusbarang':
                $dbarang    = json_decode($distribusi->barang,TRUE);
                unset($dbarang[$request->kode_barang]);
                Distribusi::where('id',$distribusi->id)->update([
                    'barang' => json_encode($dbarang)
                ]);
                return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('danger','Barang '.$request->nama_barang.' telah dihapus');
                break;
            case 'distribusikan':
                $dbarang    = json_decode($distribusi->barang);
                foreach ($dbarang as $item) {
                    $barang     = Barang::where('kode_barang',$item->kode_barang)->first();
                    if ($barang) {
                        $stok   = $barang->stok + $item->jumlah;
                        Barang::where('id',$barang->id)->update([
                            'harga_beli' => $item->harga_beli,
                            'harga_jual' => $item->harga_jual,
                            'stok' => $stok,
                        ]);
                    }
                }
                Distribusi::where('id',$distribusi->id)->update([
                    'status_stok' => $request->status_stok
                ]);
                return redirect('distribusi/'.Crypt::encryptString($distribusi->id))->with('success','Barang telah di distribusikan');
                break;
            
            default:
                # code...
                break;
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Distribusi $distribusi)
    {
        $distribusi->delete();

        return back()->with('dd','Distribusi');
    }
}
