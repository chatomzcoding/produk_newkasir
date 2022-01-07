<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Distribusi;
use App\Models\Retur;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu   = 'retur';
        $user   = Auth::user();
        $akses  = Userakses::where('user_id',$user->id)->first();
        $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : 'semua' ;
        if ($tanggal == 'semua') {
            $retur  = DB::table('retur')
                        ->join('distribusi','retur.distribusi_id','=','distribusi.id')
                        ->select('retur.*','distribusi.kode_distribusi','distribusi.no_faktur')
                        ->where('distribusi.cabang_id',$akses->cabang_id)
                        ->get();
        } else {
            $retur  = DB::table('retur')
                        ->join('distribusi','retur.distribusi_id','=','distribusi.id')
                        ->select('retur.*','distribusi.kode_distribusi','distribusi.no_faktur')
                        ->where('distribusi.cabang_id',$akses->cabang_id)
                        ->whereDate('retur.created_at',$tanggal)
                        ->get();
        }
        
        $distribusi     = Distribusi::select('id','kode_distribusi','no_faktur')->where('cabang_id',$akses->cabang_id)->get();
        $statistik  = [
            'total' => Retur::where('cabang_id',$akses->cabang_id)->count(),
            'totalproses' => Retur::where('cabang_id',$akses->cabang_id)->where('status_retur','proses')->count(),
        ];
        $filter     = [
            'tanggal' => $tanggal
        ];
        return view('gudang.retur.index', compact('menu','akses','retur','distribusi','statistik','filter'));
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
        Retur::create($request->all());
        $retur  = Retur::where('kode_retur',$request->kode_retur)->first();
        return redirect('retur/'.Crypt::encryptString($retur->id))->with('success','Retur telah dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retur  $retur
     * @return \Illuminate\Http\Response
     */
    public function show($retur)
    {
        $menu           = 'retur';
        $retur          = Retur::find(Crypt::decryptString($retur));
        $distribusi     = Distribusi::find($retur->distribusi_id);
        
        return view('gudang.retur.show', compact('menu','retur','distribusi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retur  $retur
     * @return \Illuminate\Http\Response
     */
    public function edit(Retur $retur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Retur  $retur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        switch ($request->s) {
            case 'editbarang':
                $retur  = Retur::find($request->id);
                $kodebarang = $request->kode_barang;
                if (is_null($retur->barang)) {
                    $dbarang[$request->kode_barang] = [
                        'kode_barang' => $kodebarang,
                        'nama_barang' => $request->nama_barang,
                        'jumlah' => $request->jumlahretur,
                    ];
                } else {
                    $dbarang    = json_decode($retur->barang_retur,TRUE);
                    if (isset($dbarang[$kodebarang])) {
                        $dbarang[$kodebarang]['jumlah'] = $request->jumlahretur;
                    } else {
                        $barang[$request->kode_barang] = [
                            'kode_barang' => $kodebarang,
                            'nama_barang' => $request->nama_barang,
                            'jumlah' => $request->jumlahretur,
                        ];
                        $dbarang = array_merge($dbarang,$barang);
                    }
                }
                Retur::where('id',$retur->id)->update([
                    'barang_retur' => json_encode($dbarang)
                ]);
                return back()->with('success','Barang '.$request->nama_barang.' berhasil ditambahkan ke list retur');
                break;
            case 'returbarang':
                $retur  = Retur::find($request->id);
                // kurangi stok barang dari hasil retur
                $dbarang    = json_decode($retur->barang_retur);
                foreach ($dbarang as $item) {
                    $barang     = Barang::where('kode_barang',$item->kode_barang)->first();
                    if ($barang) {
                        $stok   = $barang->stok - $item->jumlah;
                        Barang::where('id',$barang->id)->update([
                            'stok' => $stok,
                        ]);
                    }
                }
                Retur::where('id',$retur->id)->update([
                    'status_retur' => $request->status_retur
                ]);
                return back()->with('success','Retur barang berhasil');
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retur  $retur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retur $retur)
    {
        $retur->delete();

        return back()->with('dd','retur');
    }
}
