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
use Illuminate\Support\Facades\DB;

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
        $page       = FALSE;
        $fkategori  = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
        $sesi       = (isset($_GET['sesi'])) ? $_GET['sesi'] : 'barang' ;
        switch ($sesi) {
            case 'cari':
                $cari       = $_GET['cari'];
                $barang     = Barang::where('kode_barang',$cari)->get();
                if (count($barang) == 0) {
                    $barang     = Barang::where('nama_barang','LIKE','%'.$cari.'%')->get();
                }
                break;
            case 'info':
                $menu   = 'infobarang';
                $barangstokbanyak       = Barang::select('id','nama_barang','stok')->orderBy('stok','DESC')->limit(10)->get();
                $transaksi              = DB::table('transaksi')
                                            ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                            ->select('transaksi.keranjang')
                                            ->where('transaksi.keranjang','<>','NULL')
                                            ->get();
                $barang         = [];
                $totalomzet     = 0;
                foreach ($transaksi as $item) {
                    $keranjang  = json_decode($item->keranjang);
                    foreach ($keranjang as $key) {
                        if (isset($barang[$key->kode_barang])) {
                            $jumlah     = $barang[$key->kode_barang];
                            $jumlah     = $jumlah + $key->jumlah;
                            $barang[$key->kode_barang] = $jumlah;
                        } else {
                            $barang[$key->kode_barang] = $key->jumlah;
                        }
                        // total omzet
                        $totalomzet = $totalomzet + ($key->harga_jual * $key->jumlah);
                    }
                }
                arsort($barang);
                $barang = array_slice($barang,0,10);
                $barangterlaris     = [];
                foreach ($barang as $key => $value) {
                    $barang     = Barang::select('id','nama_barang')->where('kode_barang',$key)->first();
                    if ($barang) {
                        $barangterlaris[] = [
                            'barang' => $barang->nama_barang,
                            'terjual' => $value
                        ];
                    } else {
                        $barangterlaris[] = [
                            'barang' => 'barang tidak ada',
                            'terjual' => $value
                        ];
                    }
                }
                $omzetdalambarang       = 0;
                $labadalambarang        = 0;
                $barang                 = Barang::select('harga_jual','harga_beli','stok')->where('stok','<>',0)->get();
                foreach ($barang as $key) {
                    $hargajual          = $key->stok * $key->harga_jual;
                    $hargabeli          = $key->stok * $key->harga_beli;
                    $omzetdalambarang   = $omzetdalambarang + $hargajual;
                    $labadalambarang    = $labadalambarang + ($hargajual - $hargabeli);
                }
                $data   = [
                    'barangstokbanyak' => $barangstokbanyak,
                    'barangterlaris' => $barangterlaris,
                    'omzetdalambarang' => $omzetdalambarang,
                    'labadalambarang' => $labadalambarang,
                    'totalomzet' => $totalomzet,
                ];
                return view('sistem.barang.info', compact('menu','data'));
                break;
            
            default:
                if ($fkategori == 'semua') {
                    $barang      = Barang::orderBy('id','DESC')->get();
                    if (count($barang) > 250) {
                        $barang      = Barang::orderBy('id','DESC')->paginate(20);
                        $page           = TRUE;
                    }
                } else {
                    $barang     = Barang::cabangPerKategori($akses->cabang_id,$fkategori);
                }
                break;
        }
        
        $kategori       = Kategori::where('label','kategori')->orderBy('nama','ASC')->get();
        $totalbarang    = Barang::count();
        $totalitem      = Barang::sum('stok');
        $totalbarangstokkosong    = Barang::where('stok','<=',0)->count();
        $filter         = [
            'kategori' => $fkategori,
            'page' => $page,
            'sesi' => $sesi,
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
        $satuan         = Kategori::where('label','satuan')->orderBy('nama','ASC')->get();
        $kategori       = Kategori::where('label','kategori')->orderBy('nama','ASC')->get();
        $produsen       = Kategori::where('label','produsen')->orderBy('nama','ASC')->get();
        return view('sistem.barang.create', compact('satuan','kategori','produsen','menu'));
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
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'produsen_id' => $request->produsen_id,
            'satuan_id' => $request->satuan_id,
            'harga_beli' => cikararesetrupiah($request->harga_beli),
            'harga_jual' => cikararesetrupiah($request->harga_jual),
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
    public function edit($barang)
    {
        $menu   = 'barang';
        $barang     = Barang::find(Crypt::decryptString($barang));
        $satuan         = Kategori::where('label','satuan')->orderBy('nama','ASC')->get();
        $kategori       = Kategori::where('label','kategori')->orderBy('nama','ASC')->get();
        $produsen       = Kategori::where('label','produsen')->orderBy('nama','ASC')->get();
        return view('sistem.barang.edit', compact('menu','barang','satuan','kategori','produsen'));
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
            deletefile($tujuan_upload.'/'.$barang->gambar);
        } else {
            $gambar  = $barang->gambar;
        }
        Barang::where('id',$barang->id)->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'produsen_id' => $request->produsen_id,
            'satuan_id' => $request->satuan_id,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'kode_barcode' => $request->kode_barcode,
            'merk' => $request->merk,
            'status_barang' => $request->status_barang,
            'gambar' => $gambar,
        ]);

        return redirect('barang/'.Crypt::encryptString($barang->id))->with('du','Barang');
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
