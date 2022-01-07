<?php

namespace App\Http\Controllers\Kasir;

use App\Helpers\Cikara\DbCikara;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu       = 'barang';
        $user       = Auth::user();
        $akses      = Userakses::where('user_id',$user->id)->first();
        $fkategori  = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
        $cari       = (isset($_GET['cari'])) ? $_GET['cari'] : NULL ;
        if (!is_null($cari)) {
            // cek jika dicari dengan kode barang
            $barang     = Barang::where('kode_barang',$cari)->get();
            if (count($barang) == 0) {
                $barang     = Barang::where('cabang_id',$akses->cabang_id)->where('nama_barang','LIKE','%'.$_GET['cari'].'%')->get();
            }
            $fkategori  = 'cari';
            $page       = FALSE;
        } else {
            if ($fkategori == 'semua') {
                $barang      = Barang::where('cabang_id',$akses->cabang_id)->orderBy('id','DESC')->paginate(20);
                $page           = TRUE;
            } else {
                $barang     = Barang::cabangPerKategori($akses->cabang_id,$fkategori);
                $page       = FALSE;
            }
        }
        
        $kategori       = Kategori::where('cabang_id',$akses->cabang_id)->where('label','kategori')->orderBy('nama','ASC')->get();
        $totalbarang    = Barang::where('cabang_id',$akses->cabang_id)->count();
        $totalitem      = Barang::where('cabang_id',$akses->cabang_id)->sum('stok');
        $totalbarangstokkosong    = Barang::where('cabang_id',$akses->cabang_id)->where('stok','<=',0)->count();
        $filter         = [
            'kategori' => $fkategori,
            'page' => $page,
        ];
        $statistik      = [
            'totalbarang' => $totalbarang,
            'totalitem' => $totalitem,
            'totalbarangstokkosong' => $totalbarangstokkosong
        ];

        return view('sistem.barang.index', compact('menu','user','barang','kategori','filter','statistik'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu           = 'barang';
        $akses          = Userakses::where('user_id',Auth::user()->id)->first();
        $satuan         = Kategori::where('cabang_id',$akses->cabang_id)->where('label','satuan')->orderBy('nama','ASC')->get();
        $kategori       = Kategori::where('cabang_id',$akses->cabang_id)->where('label','kategori')->orderBy('nama','ASC')->get();
        $produsen       = Kategori::where('cabang_id',$akses->cabang_id)->where('label','produsen')->orderBy('nama','ASC')->get();
        return view('sistem.barang.create', compact('satuan','kategori','produsen','menu','akses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->gambar)) {
            $request->validate([
                'gambar' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',
            ]);
            // menyimpan data file yang diupload ke variabel $file
            $file = $request->file('gambar');
            
            $gambar = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'public/img/barang';
            // isi dengan nama folder tempat kemana file diupload
            $file->move($tujuan_upload,$gambar);
        } else {
            $gambar  = NULL;
        }
        Barang::create([
            'cabang_id' => $request->cabang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'kode_barcode' => $request->kode_barcode,
            'merk' => $request->merk,
            'status_barang' => $request->status_barang,
            'gambar' => $gambar,
        ]);

        return redirect('barang')->with('ds','Barang');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show($barang)
    {
        $menu       = 'barang';
        $barang     = Barang::find(Crypt::decryptString($barang));
        $statistik  = [
            'stokbarang' => $barang->stok,
            'totalterjual' => DbCikara::statistikBarang('totalterjual',$barang->id)
        ];
        return view('sistem.barang.show', compact('menu','barang','statistik'));
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
    public function destroy(Barang $barang)
    {
        deletefile('public/img/barang/'.$barang->gambar);
        $barang->delete();

        return back()->with('dd','Barang');
    }
}
