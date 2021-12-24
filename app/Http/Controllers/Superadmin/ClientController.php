<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu       = 'client';
        $client     = Client::all();
        $user       = User::where('level','client')->get();
        return view('superadmin.client.index', compact('client','menu','user'));
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
        $request->validate([
            'logo' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',
        ]);
        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('logo');
        
        $logo = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'public/img/client';
        // isi dengan nama folder tempat kemana file diupload
        $file->move($tujuan_upload,$logo);

        Client::create([
            'nama_pemilik' => $request->nama_pemilik,
            'nama_toko' => $request->nama_toko,
            'jenis_retail' => $request->jenis_retail,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'user_id' => $request->user_id,
            'detail' => $request->detail,
            'tgl_bergabung' => $request->tgl_bergabung,
            'logo' => $logo,
        ]);

        return back()->with('ds','Client');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
