<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (cektoken($_GET['token'])) {
            $result = NULL;
            switch ($_GET['label']) {
                case 'satuan':
                    $result = Kategori::where('label','satuan')->get();
                    break;
                case 'barang':
                    $result = Kategori::where('label','barang')->get();
                    break;
                
                default:
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
            Kategori::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'label' => $request->label,
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
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($kategori)
    {
        if (cektoken($_GET['token'])) {
            $kategori   = Kategori::find($kategori);
            if ($kategori) {
                $kategori->delete();
        
                return response()->json([
                    'success' => 1,
                    'message' => 'success'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'data tidak ada'
                ]);
            }
            
        } else {
            return response()->json('akses terlarang');
        }
    }
}
