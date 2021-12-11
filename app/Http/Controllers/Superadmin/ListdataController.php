<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Listdata;
use Illuminate\Http\Request;

class ListdataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu   = 'listdata';
        $kategori = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
        if ($kategori == 'semua') {
            $listdata   = Listdata::all();
        } else {
            $listdata   = Listdata::where('kategori',$_GET['kategori'])->get();
        }
        return view('superadmin.listdata.index', compact('menu','listdata','kategori'));
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
        Listdata::create($request->all());

        return back()->with('ds','List Data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Listdata  $listdata
     * @return \Illuminate\Http\Response
     */
    public function show(Listdata $listdata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Listdata  $listdata
     * @return \Illuminate\Http\Response
     */
    public function edit(Listdata $listdata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listdata  $listdata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Listdata::where('id',$request->id)->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('du','List Data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Listdata  $listdata
     * @return \Illuminate\Http\Response
     */
    public function destroy($listdata)
    {
        Listdata::find($listdata)->delete();

        return back()->with('dd','List Data');
    }
}
