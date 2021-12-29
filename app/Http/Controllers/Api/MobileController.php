<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Session;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileController extends Controller
{
    public function ceklogin(Request $request)
    {
        if (!cektoken($request->token)) {
            return response()->json('akses dilarang');
        }
        $user   = User::where('email',$request->email)->first();
        if ($user) {
            $password   = $user->password;
            if (password_verify($request->password,$password)) {
                // cek sesi
                $sesi   = Session::where('user_id',$user->id)->where('payload',1)->first();
                if ($sesi) {
                    $result    = [
                        'success' => 2,
                        'message' => $sesi->user_agent,
                    ];
                } else {
                    Session::create([
                        'id' => $user->id.'.'.time(),
                        'user_id' => $user->id,
                        'payload' => 1,
                        'user_agent' => $request->nama_device,
                        'last_activity' => 0,
                    ]);
                    $success    = [
                        'success' => 1,
                        'message' => 'success',
                        'device' => $request->nama_device,
                    ];
                    $userakses  = Userakses::where('user_id',$user->id)->first();
                    $cabang     = Cabang::find($userakses->cabang_id);
                    $client     = Client::find($cabang->id);
                    $gabungan   = [
                        'user' => $user,
                        'cabang' => $cabang,
                        'client' => $client,
                    ];
                    $result     = array_merge($success,$gabungan);
                }
                
                return response()->json($result);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'login gagal'
                ]);
            }
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'email salah'
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (!cektoken($request->token)) {
            return response()->json('akses dilarang');
        }
        $session    = Session::where('user_id',$request->user_id)->first();
        if ($session) {
            $session->delete();
            return response()->json([
                'success' => 1,
                'message' => 'logout success'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'sesi tidak ada'
            ]);
        }
        
    }

    public function userakses($user)
    {
        if (!cektoken($_GET['token'])) {
            return response()->json('akses dilarang');
        }
        return Userakses::where('user_id',$user)->first();
    }

    // UBAH PASSWORD
    public function ubahpassword(Request $request)
    {
        if (!cektoken($request->token)) {
            return response()->json('akses dilarang');
        }
        $user   = User::where('id',$request->id)->where('email',$request->email)->first();
        if ($user) {
            User::where('id',$request->id)->update([
                'password' => Hash::make($request->password),
            ]);
    
            $success    = [
                'success' => 1,
                'message' => 'success'
            ];
        } else {
            $success    = [
                'success' => 0,
                'message' => 'user tidak ada'
            ];
        }
        return $success;
    }
}
