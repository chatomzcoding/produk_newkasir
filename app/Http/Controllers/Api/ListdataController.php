<?php

namespace App\Http\Controllers\Api;

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
        //
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
        Listdata::create([
            'kategori' => $request->kategori,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'success'
        ]);
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
    public function update(Request $request, Listdata $listdata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Listdata  $listdata
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listdata $listdata)
    {
        //
    }
}
