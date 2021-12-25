<?php
namespace App\Helpers\Cikara;

use App\Models\Barang;
use App\Models\Daftarakun;
use App\Models\Daftarakunpembantu;
use App\Models\Jurnalakun;
use App\Models\Kategori;
use App\Models\Keluarga;
use App\Models\Lapor;
use App\Models\Penduduk;
use App\Models\Penduduksurat;
use App\Models\Profil;
use App\Models\User;
use App\Models\Userakses;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DbCikara {

      // total data tabel
      public static function countData($table=null,$where=null)
      {
          $total = null;
          if (!is_null($table)) {
              if (!is_null($where) AND is_array($where)) {
                  if (count($where) == 2) {
                      $total = DB::table($table)
                      ->where($where[0],$where[1])
                      ->count();
                  }
              } else {
                  $total = DB::table($table)
                  ->count();
              }
              return $total;
          }
      }
  
      // tampil data table
      public static function showtable($table=null,$where=null)
      {
          if (!is_null($where) AND is_array($where)) {
              $show = DB::table($table)
              ->where($where[0],$where[1])
              ->get();
          } else {
              $show = DB::table($table)
              ->get();
          }
          return $show;
      }
      // tampil data table
      public static function showtablefirst($table,$where=null)
      {
          if (!is_null($where) AND is_array($where)) {
              $show = DB::table($table)
              ->where($where[0],$where[1])
              ->first();
          } else {
              $show = DB::table($table)
              ->first();
          }
          return $show;
      }
      
    public static function showtableid($table,$id)
    {
        $data = DB::table($table)->where('id',$id)->first();
        return $data;
    }

    // untuk dashboard

    public static function chartDashboard($sesi,$bulan=null,$tahun=null)
    {
        if (is_null($bulan)) {
            $bulan  = ambil_bulan();
        }
        if (is_null($tahun)) {
            $tahun = ambil_tahun();
        }
        switch ($sesi) {
            case 'kunjungan':
                $result = NULL;
                for ($i=1; $i <= ambil_tgl(); $i++) { 
                    $tanggal    = $tahun.'-'.$bulan.'-'.$i;
                    $jumlah    = Visitor::whereDate('created_at',$tanggal)->sum('hits');
                    if ($jumlah) {
                        $result .= $jumlah.',';
                    } else {
                        $result .= '0,';
                    }
                }
                $result = trim($result,',');
                break;
            case 'jumlahkunjungan':
                $result = Visitor::sum('hits');
                break;
            default:
                # code...
                break;
        }

        return $result;
    }

    // kode barang
    public static function kodeBarang($cabang_id)
    {
        $kode   = 'CK'.$cabang_id.'.';
        // cek barang terakhir
        $barang     = Barang::where('cabang_id',$cabang_id)->latest()->first();
        if ($barang) {
            $kodebarang     = explode('.',$barang->kode_barang);
            $nomor          = $kodebarang[1];
            $urutbaru       = $nomor + 1;
            if ($nomor < 10 ) {
                $urutan = '000'.$urutbaru;
            }elseif ($nomor >= 10 AND $nomor < 100) {
                $urutan = '00'.$urutbaru;
            }elseif ($nomor >= 100 AND $nomor < 1000) {
                $urutan = '0'.$urutbaru;
            } else {
                $urutan = $urutbaru;
            }
            $kodebarang = $kode.$urutan;
        } else {
            $kodebarang = $kode.'0001';
        }
        return $kodebarang;
        
    }

    public static function namaKategori($id)
    {
        $kategori   = Kategori::find($id);
        $result     = '-';
        if ($kategori) {
            $result = $kategori->nama;
        }
        return $result;
    }
}