<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $folder = 'public/img/user';

    public function index()
    {
        $user   = Auth::user();
        $menu   = 'user';
        switch ($user->level) {
            case 'superadmin':
                $user   = User::where('level','client')->get();
                $statistik  = [
                    'totaluser' => User::count(),
                    'totalclient' => count($user),
                    'totalcabang' => Cabang::count(),
                ];
                return view('superadmin.user.index', compact('user','menu','statistik'));
                break;
            case 'cabang':
                $cabang     = Cabang::where('user_id',$user->id)->first();
                $user   = DB::table('users')
                            ->join('user_akses','users.id','=','user_akses.user_id')
                            ->select('users.*')
                            ->where('user_akses.cabang_id',$cabang->id)
                            ->get();
                $statistik  = [
                    'totalkaryawan' => Userakses::where('cabang_id',$cabang->id)->count(),
                    'totalgudang' => DB::table('users')
                                        ->join('user_akses','users.id','=','user_akses.user_id')
                                        ->where('users.level','gudang')
                                        ->where('user_akses.cabang_id',$cabang->id)
                                        ->count(),
                    'totalkasir' => DB::table('users')
                                        ->join('user_akses','users.id','=','user_akses.user_id')
                                        ->where('user_akses.cabang_id',$cabang->id)
                                        ->where('users.level','kasir')
                                        ->count(),
                ];
                return view('cabang.user.index', compact('user','menu','statistik'));
                break;
            
            default:
                return redirect('dashboard');
                break;
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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);

        if ($request->level == 'gudang' || $request->level == 'kasir') {
            $user       = User::where('email',$request->email)->first();
            $cabang     = Cabang::where('user_id',Auth::user()->id)->first();
            // $cabang     = Caba
            // tambahkan ke user akses
            Userakses::create([
                'user_id' => $user->id,
                'cabang_id' => $cabang->id,
            ]);
        }

        return redirect()->back()->with('ds','User');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        $user   = User::find(Crypt::decryptString($user));
        $menu   = 'pengaturan';
        return view('sistem.edituser', compact('user','menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user   = User::find($request->id);
        // profile_photo_path
        if (isset($request->profile_photo_path)) {
            $request->validate([
                'profile_photo_path' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',
            ]);
            // menyimpan data file yang diupload ke variabel $file
            $file = $request->file('profile_photo_path');
            
            $nama_file = time()."_".$file->getClientOriginalName();
            
            // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = $this->folder;
            $file->move($tujuan_upload,$nama_file);
        } else {
            $nama_file = $user->profile_photo_path;
        }
        
        if (isset($request->password)) {
            User::where('id',$request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'profile_photo_path' => $nama_file,
                'password' => Hash::make($request->password),
            ]);
        } else {
            User::where('id',$request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'profile_photo_path' => $nama_file,
            ]);
        }
        

        return redirect()->back()->with('du','User');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        User::find($user)->delete();

        return redirect()->back()->with('dd','User');
    }
}
