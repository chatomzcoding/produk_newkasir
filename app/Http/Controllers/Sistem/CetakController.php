<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Distribusi;
use App\Models\Transaksi;
use App\Models\Userakses;
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
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan();
                $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
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
                        switch ($_GET['filter']) {
                            case 'harian':
                                $transaksi      = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->get();
                                $data           = [
                                    'sesi' => 'Kasir : '.$user->name,
                                    'info' => 'Tanggal '.date_indo($tanggal),
                                ];
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'gudang':
                        $akses  = Userakses::where('user_id',$user->id)->first();
                        switch ($_GET['filter']) {
                            case 'harian':
                                $transaksi      = DB::table('transaksi')
                                                    ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                                    ->select('transaksi.*')
                                                    ->whereDate('transaksi.created_at',$tanggal)
                                                    ->where('user_akses.cabang_id',$akses->cabang_id)
                                                    ->get();
                                $data           = [
                                    'sesi' => 'Gudang : '.$user->name,
                                    'info' => 'Tanggal '.date_indo($tanggal),
                                ];
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }
                $pdf        = PDF::loadview('sistem.cetak.transaksi', compact('transaksi','data'));
                $namafile   = 'Transaksi';
                break;
            
            case 'barang':
                $namafile   = 'Barang';
                $akses          = Userakses::where('user_id',$user->id)->first();
                $kategori       = $_GET['kategori'];
                if ($kategori == 'semua') {
                    $barang     = Barang::where('cabang_id',$akses->cabang_id)->get();
                } else {
                    $barang     =  Barang::cabangPerKategori($akses->cabang_id,$kategori);
                }
                if (isset($_GET['harga'])) {
                    $cabang     = Cabang::find($akses->cabang_id);
                    $client     = Client::find($cabang->client_id);
                    $pdf        = PDF::loadview('sistem.cetak.hargabarang', compact('barang','kategori','client'));
                } else {
                    $pdf        = PDF::loadview('sistem.cetak.barang', compact('barang','kategori'));
                }
                break;
            case 'distribusi':
                $namafile   = 'Distribusi';
                $akses  = Userakses::where('user_id',$user->id)->first();
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : 'semua' ;
                if ($tanggal == 'semua') {
                    $datatabel  = Distribusi::where('cabang_id',$akses->cabang_id)->orderBy('tgl_faktur','DESC')->get();
                    $info       = 'Daftar Distribusi'; 
                } else {
                    $datatabel = Distribusi::where('cabang_id',$akses->cabang_id)->whereDate('tgl_faktur',$tanggal)->get();
                    $info       = 'Daftar Distribusi tanggal '.date_indo($tanggal); 
                }
                $data       = [
                    'info' => $info
                ];
                $pdf        = PDF::loadview('sistem.cetak.distribusi', compact('datatabel','data'));
                
                break;
            default:
                return 'sesi tidak ada';
                break;
        }
        return $pdf->download($namafile.'.pdf');

    }
}
