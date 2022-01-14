<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Client;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\Userakses;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CetakController extends Controller
{
    public function cetak()
    {
        $user   = Auth::user();
        switch ($user->level) {
            case 'client':
                $client     = Client::where('user_id',$user->id)->first();
                $alamat     = $client->alamat;
                $telp     = $client->no_telp;
                break;
            case 'cabang':
                $cabang     = Cabang::where('user_id',$user->id)->first();
                $client     = Client::find($cabang->client_id);
                $alamat     = $cabang->alamat;
                $telp     = $cabang->telp;
                break;
            
            default:
                $userakses  = Userakses::where('user_id',$user->id)->first();
                $cabang     = Cabang::find($userakses->cabang_id);
                $client     = Client::find($cabang->client_id);
                $alamat     = $cabang->alamat;
                $telp     = $cabang->telp;

                break;
        }
        $main       = [
            'telp' => $telp,
            'alamat' => $alamat,
            'client' => $client,
            'cabang' => $cabang,
        ];
        switch ($_GET['s']) {
            case 'transaksi':
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan();
                $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                $kategori = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua';
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
                        $filter = (isset($_GET['sesi'])) ? $_GET['sesi'] : 'harian' ;
                        switch ($filter) {
                            case 'harian':
                                $transaksi      = Transaksi::where('user_id',$user->id)->whereDate('created_at',$tanggal)->get();
                                $data           = [
                                    'sesi' => 'Kasir : '.$user->name,
                                    'info' => 'Tanggal '.date_indo($tanggal),
                                    'alamat' => $alamat,
                                    'telp' => $telp,
                                ];
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'gudang':
                        $akses  = Userakses::where('user_id',$user->id)->first();
                        $sesi   = 'Cabang : '.ucwords($cabang->nama_cabang);
                        switch ($_GET['filter']) {
                            case 'harian':
                                $transaksi      = DB::table('transaksi')
                                                    ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                                    ->select('transaksi.*')
                                                    ->whereDate('transaksi.created_at',$tanggal)
                                                    ->where('user_akses.cabang_id',$akses->cabang_id)
                                                    ->get();
                                $data           = [
                                    'sesi' => $sesi,
                                    'info' => 'Tanggal '.date_indo($tanggal),
                                    'alamat' => $alamat,
                                    'telp' => $telp,
                                ];
                                break;
                            case 'bulanan':
                                $transaksi  = DB::table('transaksi')
                                                ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                                ->select('transaksi.*')
                                                ->where('user_akses.cabang_id',$akses->cabang_id)
                                                ->whereMonth('transaksi.created_at',$bulan)
                                                ->whereYear('transaksi.created_at',$tahun)
                                                ->get();
                                $data           = [
                                    'sesi' => $sesi,
                                    'info' => 'Bulan '.bulan_indo($bulan).' '.$tahun,
                                    'alamat' => $alamat,
                                    'telp' => $telp,
                                ];
                                break;
                            case 'tahunan':
                                $transaksi  = DB::table('transaksi')
                                                ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                                ->select('transaksi.*')
                                                ->where('user_akses.cabang_id',$akses->cabang_id)
                                                ->whereYear('transaksi.created_at',$tahun)
                                                ->get();
                                $data           = [
                                    'sesi' => $sesi,
                                    'info' => 'Tahun '.$tahun,
                                    'alamat' => $alamat,
                                    'telp' => $telp,
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
                if ($kategori <> 'semua') {
                    $barang     = Barang::select('kode_barang')->where('cabang_id',$akses->cabang_id)->where('kategori_id',$kategori)->pluck('kode_barang')->toArray();
                    $kategori   = Kategori::find($kategori);
                    $dtransaksi = [];
                    foreach ($transaksi as $item) {
                        if (!is_null($item->keranjang)) {
                            $keranjang  = json_decode($item->keranjang,TRUE);
                            foreach ($keranjang as $key) {
                                // cek barang kategori
                                if (in_array($key['kode_barang'],$barang)) {
                                    if (isset($dtransaksi[$key['kode_barang']])) {
                                        $dbaru[]      = $key;
                                        $lkeranjang     = $dtransaksi[$key['kode_barang']];
                                        $dtransaksi[$key['kode_barang']] = array_merge($lkeranjang,$dbaru);
                                        $dbaru          = NULL;
                                    } else {
                                        $dtransaksi[$key['kode_barang']] = [
                                            $key
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    $namafile   = 'Transaksi Kategori '.ucwords($kategori->nama).' - '.$data['info'];
                    $data           = [
                        'sesi' => 'Kategori '.ucwords($kategori->nama),
                        'info' => $data['info'],
                    ];
                    $pdf        = PDF::loadview('sistem.cetak.transaksikategori', compact('dtransaksi','data','main'));
                } else {
                    $namafile   = 'Transaksi';
                    $pdf        = PDF::loadview('sistem.cetak.transaksi', compact('transaksi','data','main'));
                }
                break;
            
            case 'barang':
                $akses          = Userakses::where('user_id',$user->id)->first();
                $kategori       = $_GET['kategori'];
                $kategori       = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua' ;
                if ($kategori == 'semua') {
                    $namafile   = 'Barang';
                    $dkategori  = NULL;
                    $barang     = Barang::where('cabang_id',$akses->cabang_id)->get();
                } else {
                    $dkategori  = Kategori::find($kategori);
                    $namafile   = 'Barang Kategori '.ucwords($dkategori->nama);
                    $barang     =  Barang::cabangPerKategori($akses->cabang_id,$kategori);
                }
                if (isset($_GET['harga'])) {
                    $pdf        = PDF::loadview('sistem.cetak.hargabarang', compact('barang','kategori','main'));
                } else {
                    $data       = [
                        'kategori' => $kategori
                    ];
                    $pdf        = PDF::loadview('sistem.cetak.barang', compact('barang','dkategori','data','main'));
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
            case 'kategoribarang':
                $namafile   = 'Data Kategori Barang';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $kategori   = Kategori::kategori($akses->cabang_id);
                $pdf        = PDF::loadview('sistem.cetak.kategoribarang', compact('kategori'));

                break;
            case 'satuanbarang':
                $namafile   = 'Data Satuan Barang';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $satuan   = Kategori::satuan($akses->cabang_id);
                $pdf        = PDF::loadview('sistem.cetak.satuanbarang', compact('satuan'));

                break;
            case 'supplier':
                $namafile   = 'Data Supplier';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $supplier   = Supplier::where('cabang_id',$akses->cabang_id)->orderBy('nama_supplier','ASC')->get();
                $pdf        = PDF::loadview('sistem.cetak.supplier', compact('supplier'));

                break;
            case 'produsen':
                $namafile   = 'Data Produsen';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $produsen   = Kategori::produsen($akses->cabang_id);

                $pdf        = PDF::loadview('sistem.cetak.produsen', compact('produsen'));

                break;
            case 'detailbarang':
                $barang     = Barang::find(Crypt::decryptString($_GET['id']));
                $namafile   = 'Detail Barang '.$barang->nama_barang;
                $pdf        = PDF::loadview('sistem.cetak.detailbarang', compact('main','barang'));

                break;
                default:
                return 'sesi tidak ada';
                break;
        }
        return $pdf->download($namafile.'.pdf');

    }
}
