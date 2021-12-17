<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Session;
use App\Models\User;
use App\Models\Userakses;
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
                // Session::create([
                //     'id' => $user->id.time(),
                //     'user_id' => $user->id,
                //     'payload' => 1,
                //     'user_agent' => $request->nama_device,
                //     'last_activity' => 0,
                // ]);
                $success    = [
                    'success' => 1,
                    'message' => 'success'
                ];
                $userakses  = Userakses::where('user_id',$user->id)->first();
                $client     = Client::find($userakses->client_id);
                $gabungan   = [
                    'user' => $user,
                    'client' => $client,
                ];
                $result     = array_merge($success,$gabungan);
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

    public function userakses($user)
    {
        if (!cektoken($_GET['token'])) {
            return response()->json('akses dilarang');
        }
        return Userakses::where('user_id',$user)->first();
    }
}
