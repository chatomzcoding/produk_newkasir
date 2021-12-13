<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;

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
                Session::create([
                    'id' => $user->id.time(),
                    'user_id' => $user->id,
                    'payload' => 1,
                    'user_agent' => $request->nama_device,
                    'last_activity' => 0,
                ]);
                return response()->json([
                    'success' => 1,
                    'message' => 'success'
                ]);
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
}
