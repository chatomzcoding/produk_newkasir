<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Session;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Userakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Crypt;

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

    public function encryptid(Request $request)
    {
        $id =   Crypt::encryptString($request->id);

        return [
            'id' => $id,
        ];
    }

    public function cetaktransaksi()
    {
        
        $user       = User::find(Crypt::decryptString($_GET['user_id']));
        switch ($user->level) {
            case 'kasir':
                switch ($_GET['sesi']) {
                    case 'tanggal':
                        $tanggal    = $_GET['tanggal'];
                        $transaksi      = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->get();
                        $data           = [
                            'nama_kasir' => $user->name,
                            'info' => 'Tanggal '.date_indo($tanggal),
                        ];
                        $namafile   = 'Transaksi per Tanggal '.$tanggal;

                        break;
                    case 'bulan':
                        $bulan    = $_GET['bulan'];
                        $tahun    = $_GET['tahun'];
                        $transaksi      = Transaksi::where('user_id',$user->id)->wheremonth('created_at',$bulan)->whereyear('created_at',$tahun)->get();
                        $data           = [
                            'nama_kasir' => $user->name,
                            'info' => 'Bulan '.bulan_indo($bulan).' '.$tahun,
                        ];
                        $namafile   = 'Transaksi per Bulan '.$bulan.' '.$tahun;

                        break;
                    
                    default:
                        # code...
                        break;
                }
                $pdf        = PDF::loadview('sistem.cetak.transaksi', compact('transaksi','data'));
                break;
            
            default:
                return 'sesi tidak ada';
                break;
        }
        return $pdf->download($namafile.'.pdf');
    }
}
