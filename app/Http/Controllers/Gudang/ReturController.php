<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
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
        $retur  = DB::table('retur')
                    ->join('distribusi','retur.distribusi_id','=','distribusi.id')
                    ->select('retur.*','distribusi.kode','distribusi.no_faktur')
                    ->where('distribusi.cabang_id',$akses->cabang_id)
                    ->get();
        $distribusi     = Distribusi::select('id','kode','no_faktur')->where('cabang_id',$akses->cabang_id)->get();
        return view('gudang.retur.index', compact('menu','akses','retur','distribusi'));
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
    public function update(Request $request, Retur $retur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retur  $retur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retur $retur)
    {
        //
    }
}
