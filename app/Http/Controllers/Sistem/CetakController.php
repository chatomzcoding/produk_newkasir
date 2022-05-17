<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Client;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\User;
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
        $client     = Client::first();
        $alamat     = $client->alamat;
        $telp     = $client->telp;
        $main       = [
            'client' => $client
        ];
        switch ($_GET['s']) {
            case 'transaksi':
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : tgl_sekarang();
                $bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : ambil_bulan();
                $tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : ambil_tahun();
                $kategori = (isset($_GET['kategori'])) ? $_GET['kategori'] : 'semua';
                switch ($user->level) {
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
                        $sesi   = '';
                        switch ($_GET['filter']) {
                            case 'harian':
                                $transaksi      = Transaksi::whereDate('transaksi.created_at',$tanggal)->get();
                                $data           = [
                                    'sesi' => $sesi,
                                    'info' => 'Tanggal '.date_indo($tanggal),
                                    'alamat' => $alamat,
                                    'telp' => $telp,
                                ];
                                break;
                            case 'bulanan':
                                $transaksi  = Transaksi::whereMonth('transaksi.created_at',$bulan)
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
                                $transaksi  = Transaksi::whereYear('transaksi.created_at',$tahun)->get();
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
                    $barang     = Barang::select('kode_barang')->where('kategori_id',$kategori)->pluck('kode_barang')->toArray();
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
                    $barang     = Barang::get();
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
                $tanggal = (isset($_GET['tanggal'])) ? $_GET['tanggal'] : 'semua' ;
                if ($tanggal == 'semua') {
                    $datatabel  = Distribusi::orderBy('tgl_faktur','DESC')->get();
                    $info       = 'Daftar Distribusi'; 
                } else {
                    $datatabel = Distribusi::whereDate('tgl_faktur',$tanggal)->get();
                    $info       = 'Daftar Distribusi tanggal '.date_indo($tanggal); 
                }
                $data       = [
                    'info' => $info
                ];
                $pdf        = PDF::loadview('sistem.cetak.distribusi', compact('main','datatabel','data'));
                
                break;
            case 'kategoribarang':
                $namafile   = 'Data Kategori Barang';
                $kategori   = Kategori::kategori();
                $pdf        = PDF::loadview('sistem.cetak.kategoribarang', compact('main','kategori'));

                break;
            case 'satuanbarang':
                $namafile   = 'Data Satuan Barang';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $satuan     = Kategori::satuan($akses->cabang_id);
                $pdf        = PDF::loadview('sistem.cetak.satuanbarang', compact('main','satuan'));

                break;
            case 'supplier':
                $namafile   = 'Data Supplier';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $supplier   = Supplier::orderBy('nama_supplier','ASC')->get();
                $pdf        = PDF::loadview('sistem.cetak.supplier', compact('main','supplier'));

                break;
            case 'produsen':
                $namafile   = 'Data Produsen';
                $akses      = Userakses::where('user_id',$user->id)->first();
                $produsen   = Kategori::produsen($akses->cabang_id);

                $pdf        = PDF::loadview('sistem.cetak.produsen', compact('main','produsen'));

                break;
            case 'detailbarang':
                $barang     = Barang::find(Crypt::decryptString($_GET['id']));
                $namafile   = 'Detail Barang '.$barang->nama_barang;
                $pdf        = PDF::loadview('sistem.cetak.detailbarang', compact('main','barang'));

                break;
            case 'user':
                switch ($user->level) {
                    case 'value':
                        # code...
                        break;
                    
                    default:
                        $namafile   = 'Data User Client';
                        $user       = User::where('level','client')->get();
                        break;
                }
                $pdf        = PDF::loadview('sistem.cetak.userclient', compact('user'));

                break;
            case 'cabang':
               
                $namafile   = 'Data Cabang';
                $cabang     = Cabang::where('client_id',$client->id)->get();
                $pdf        = PDF::loadview('sistem.cetak.cabang', compact('main','cabang'));
                break;
            case 'karyawan':
               
                $namafile   = 'Data Karyawan';
                $user   = DB::table('users')
                            ->join('user_akses','users.id','=','user_akses.user_id')
                            ->select('users.*')
                            ->where('user_akses.cabang_id',$cabang->id)
                            ->get();
                $pdf        = PDF::loadview('sistem.cetak.karyawan', compact('main','user'));
                break;
            default:
                return 'sesi tidak ada';
                break;
        }
        return $pdf->download($namafile.'.pdf');

    }
}
