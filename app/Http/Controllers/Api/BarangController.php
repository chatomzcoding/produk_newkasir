<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (cektoken($_GET['token'])) {
            if (isset($_GET['filter'])) {
                return Barang::where('client_id',$_GET['client_id'])->where($_GET['field'],$_GET['nilai_field'])->orderBy($_GET['field_sortby'],$_GET['sortby'])->get();
            } else {
                if (isset($_GET['kategori'])) {
                    return Barang::where('client_id',$_GET['client_id'])->where('kategori_id',$_GET['kategori_id'])->orderBy('nama_barang',$_GET['sortby'])->get();
                } else {
                    return Barang::where('client_id',$_GET['client_id'])->orderBy('nama_barang',$_GET['sortby'])->get();
                }
            }
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
            $namafile   = uploadgambar($request,'barang');
            Barang::create([
                'client_id' => $request->client_id,
                'kode_barang' => 'KD'.time(),
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
                'gambar' => $namafile,
                'kode_barcode' => $request->kode_barcode,
                'merk' => $request->merk,
                'produsen_id' => $request->produsen_id,
            ]);
    
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
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($barang)
    {
        
        if (cektoken($_GET['token'])) {
            $barang     = Barang::find($barang);
            deletefile('public/img/barang/'.$barang->gambar);
    
            $barang->delete();
    
            return response()->json([
                'success' => 1,
                'message' => 'success'
            ]);
        } else {
            return response()->json('akses terlarang');
        }
    }
}
