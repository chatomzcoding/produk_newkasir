<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function ceklogin(Request $request)
    {
        $user   = User::where('email',$request->email)->first();
        if ($user) {
            $password   = $user->password;
            if (password_verify($request->password,$password)) {
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
