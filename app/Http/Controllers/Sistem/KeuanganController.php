<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
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
        if (isset($request->sesi)) {
            $keuangan   = Keuangan::find($request->keuangan_id);
            $id         = 'KR.'.time();
            if (is_null($keuangan->rincian) || empty($keuangan->rincian)) {
                $rincian[$id]    = [
                    'id' => $id,
                    'kategori' => $request->kategori,
                    'keterangan' => $request->keterangan,
                    'nominal' => cikararesetrupiah($request->nominal),
                ];
            } else {
                $drincian       = json_decode($keuangan->rincian,TRUE);
                $rincian[$id]    = [
                    'id' => $id,
                    'kategori' => $request->kategori,
                    'keterangan' => $request->keterangan,
                    'nominal' => cikararesetrupiah($request->nominal),
                ];

                $rincian    = array_merge($rincian,$drincian);
            }

            Keuangan::where('id',$keuangan->id)->update([
                'rincian' => json_encode($rincian)
            ]);
            
            $info   = 'Rincian telah ditambahkan';
        } else {
            Keuangan::create([
                'cabang_id' => $request->cabang_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'penjualan' => 0,
            ]);
            $info   = 'Laporan telah ditambahkan';
        }
        
        return back()->with('success',$info);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keuangan  $keuangan
     * @return \Illuminate\Http\Response
     */
    public function show(Keuangan $keuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Keuangan  $keuangan
     * @return \Illuminate\Http\Response
     */
    public function edit(Keuangan $keuangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Keuangan  $keuangan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keuangan $keuangan)
    {
        switch ($request->sesi) {
            case 'hapusitem':
                $rincian    = json_decode($keuangan->rincian,TRUE);
                unset($rincian[$request->id]);
                Keuangan::where('id',$keuangan->id)->update([
                    'rincian' => json_encode($rincian)
                ]);

                return back()->with('dd','Item');
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keuangan  $keuangan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keuangan $keuangan)
    {
        //
    }
}
