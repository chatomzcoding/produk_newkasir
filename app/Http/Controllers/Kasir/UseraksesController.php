<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UseraksesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu       = 'userakses';
        $user       = Auth::user();
        $userakses  = Userakses::where('user_id',$user->id)->first();

        return view('kasir.userakses', compact('user','userakses','menu'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Userakses  $userakses
     * @return \Illuminate\Http\Response
     */
    public function show(Userakses $userakses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Userakses  $userakses
     * @return \Illuminate\Http\Response
     */
    public function edit(Userakses $userakses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Userakses  $userakses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Userakses $userakses)
    {
        Userakses::where('id',$request->id)->update([
            'app_key' => $request->app_key,
            'app_port' => $request->app_port,
        ]);

        return back()->with('sucess','Userakses sudah diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Userakses  $userakses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userakses $userakses)
    {
        //
    }
}
