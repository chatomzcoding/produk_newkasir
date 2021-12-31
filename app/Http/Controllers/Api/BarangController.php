<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Cikara\DbCikara;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Client;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!cektoken($_GET['token'])) {
            return response()->json('akses terlarang');
        }
        if (isset($_GET['keranjang'])) {
            $transaksi  = Transaksi::find($_GET['transaksi_id']);
            if ($transaksi) {
                if (!is_null($transaksi->keranjang)) {
                    return json_decode($transaksi->keranjang);
                } else {
                   $barang = []; 
                }
                return $barang;
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'transaksi tidak ada'
                ]);
            }
        }
        $kategori   = Kategori::where('label','kategori')->orderby('nama','ASC')->get();
        $satuan   = Kategori::where('label','satuan')->orderby('nama','ASC')->get();


        if (isset($_GET['filter'])) {
            $barang = Barang::where('cabang_id',$_GET['cabang_id'])->where($_GET['field'],$_GET['nilai_field'])->orderBy($_GET['field_sortby'],$_GET['sortby'])->get();
        } else {
            if (isset($_GET['kategori_id'])) {
                $barang = Barang::where('cabang_id',$_GET['cabang_id'])->where('kategori_id',$_GET['kategori_id'])->orderBy('nama_barang',$_GET['sortby'])->get();
            } else {
                $barang = Barang::where('cabang_id',$_GET['cabang_id'])->orderBy('nama_barang',$_GET['sortby'])->get();
            }
        }

        $result[]     = [
            'barang' => $barang,
            'kategori' => $kategori,
            'satuan' => $satuan,
        ];

        return $result;
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (cektoken($request->token)) {
            if (is_null($request->image) || $request->image == '') {
                $namafile   = NULL;
            } else {
                $namafile   = uploadgambar($request,'barang');
            }
            Barang::create([
                'cabang_id' => $request->cabang_id,
                'kode_barang' => DbCikara::kodeBarang($request->cabang_id),
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
        if (cektoken($request->token)) {
            if (is_null($request->image) || $request->image == '') {
                $namafile   = NULL;
            } else {
                $namafile   = uploadgambar($request,'barang');
            }
            Barang::where('id',$barang->id)->update([
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($barang)
    {
        if (!cektoken($_GET['token'])) {
            return response()->json('akses terlarang');
        }

        if (!isset($_GET['semua'])) {
            $barang     = Barang::find($barang);
            if ($barang) {
                deletefile('public/img/barang/'.$barang->gambar);
        
                $barang->delete();
        
                return response()->json([
                    'success' => 1,
                    'message' => 'success'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'barang tidak ada'
                ]);
            }
        } else {
            // cek client
            $client     = Client::find($barang);
            if ($client) {
                Barang::where('client_id',$barang)->delete();
                return response()->json([
                    'success' => 1,
                    'message' => 'success'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'client tidak ada'
                ]);
            }
        }
        
            
    }
}
