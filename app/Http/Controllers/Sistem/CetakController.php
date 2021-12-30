<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CetakController extends Controller
{
    public function cetak()
    {
        $user   = Auth::user();
        
        switch ($_GET['s']) {
            case 'transaksi':
                $tanggal    = $_GET['tanggal'];
                switch ($user->level) {
                    case 'cabang':
                        $cabang     = Cabang::where('user_id',$user->id)->first();
                        $transaksi      = DB::table('transaksi')
                            ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                            ->where('user_akses.cabang_id',$cabang->id)
                            ->select('transaksi.*')
                            ->get();
                        $data = [
                            
                        ];
                        break;
                    case 'kasir':
                        $transaksi      = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->get();
                        $data           = [
                            'nama_kasir' => $user->name
                        ];
                        break;
                    
                    default:
                        # code...
                        break;
                }
                $pdf        = PDF::loadview('sistem.cetak.transaksi', compact('transaksi','tanggal','data'));
                $namafile   = 'Transaksi';
                break;
            
            default:
                # code...
                break;
        }
        return $pdf->download($namafile.'.pdf');

    }
}
