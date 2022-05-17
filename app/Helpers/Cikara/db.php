<?php
namespace App\Helpers\Cikara;

use App\Models\Barang;
use App\Models\Distribusi;
use App\Models\Kategori;
use App\Models\Retur;
use App\Models\Transaksi;
use App\Models\Userakses;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public static function kodeBarang($cabang_id='1')
    {
        $kode   = 'CK'.$cabang_id.'.';
        // cek barang terakhir
        $barang     = Barang::latest()->first();
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

    public static function kodeTransaksi($user_id)
    {
        $tgl    = ambil_tgl();
        $bln    = ambil_bulan();
        $thn    = substr(ambil_tahun(),2,2);
        $kode   = 'TRX'.$user_id.'.'.$tgl.$bln.$thn.'.';
        // cek transaksi terakhir
        $transaksi     = Transaksi::where('user_id',$user_id)->whereDate('created_at',tgl_sekarang())->latest()->first();
        if ($transaksi) {
            $kodetrx     = explode('.',$transaksi->kode_transaksi);
            $nomor          = $kodetrx[2];
            $urutbaru       = $nomor + 1;
            $kodebarang = $kode.$urutbaru;
        } else {
            $kodebarang = $kode.'1';
        }
        return $kodebarang;
        
    }
    public static function kodeDistribusi($user_id)
    {
        $akses          = Userakses::where('user_id',$user_id)->first();
        $kode   = 'DB'.$akses->cabang_id.'.';
        // cek distribusi terakhir
        $distribusi     = Distribusi::where('cabang_id',$akses->cabang_id)->latest()->first();
        if ($distribusi) {
            $kodetrx     = explode('.',$distribusi->kode_distribusi);
            $nomor          = $kodetrx[1];
            $urutbaru       = $nomor + 1;
            $kodedistribusi = $kode.$urutbaru;
        } else {
            $kodedistribusi = $kode.'1';
        }
        return $kodedistribusi;
        
    }
    public static function kodeRetur($user_id)
    {
        $akses          = Userakses::where('user_id',$user_id)->first();
        $kode   = 'RB'.$akses->cabang_id.'.';
        // cek distribusi terakhir
        $retur     = Retur::where('cabang_id',$akses->cabang_id)->latest()->first();
        if ($retur) {
            $kodetrx     = explode('.',$retur->kode_retur);
            $nomor          = $kodetrx[1];
            $urutbaru       = $nomor + 1;
            $koderetur = $kode.$urutbaru;
        } else {
            $koderetur = $kode.'1';
        }
        return $koderetur;
        
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

    public static function barangByKasir($user_id)
    {
        $akses      = Userakses::where('user_id',$user_id)->first();
        $result     = Barang::where('cabang_id',$akses->cabang_id)->select('id','nama_barang')->get();
        return $result;
    }

    // DASHBOARD
    public static function omzetByUser($sesi,$data)
    {
        $omzet  = 0;
        switch ($sesi) {
            case 'hariini':
                $transaksi  = Transaksi::select('keranjang')->where('user_id',$data['user_id'])->where('keranjang','<>',NULL)->whereDate('created_at',tgl_sekarang())->get();
                if (count($transaksi) > 0) {
                    foreach ($transaksi as $item) {
                        foreach (json_decode($item->keranjang) as $key) {
                            $omzet = $omzet + ($key->harga_jual * $key->jumlah);
                        }
                    }
                }
                break;
            
            case 'chartharianperbulan':
                $result    = [];
                for ($i=1; $i <= 31; $i++) {
                    $nilai = 0; 
                    $transaksi  = Transaksi::select('keranjang')->where('user_id',$data['user_id'])->where('keranjang','<>',NULL)->whereMonth('created_at',ambil_bulan())->whereDay('created_at',$i)->whereYear('created_at',ambil_tahun())->get();
                    if (count($transaksi) > 0) {
                        foreach ($transaksi as $item) {
                            foreach (json_decode($item->keranjang) as $key) {
                                $nilai = $nilai + ($key->harga_jual * $key->jumlah);
                            }
                        }
                        $result[] = [
                            'tanggal' => $i,
                            'nilai' => $nilai
                        ];
                    }
                }
                $omzet  = $result;
                break;
            
            default:
                # code...
                break;
        }

        return $omzet;
    }

    // perihal barang
    public static function statistikBarang($sesi,$id)
    {
        switch ($sesi) {
            case 'totalterjual':
                $total  = 0;
                $barang = Barang::find($id);    
                $kodebarang     = $barang->kode_barang;
                $transaksi  = DB::table('transaksi')
                                ->join('user_akses','transaksi.user_id','=','user_akses.user_id')
                                ->select('transaksi.keranjang')
                                ->where('transaksi.keranjang','<>',NULL)
                                ->where('user_akses.cabang_id',$barang->cabang_id)
                                ->get();
                foreach ($transaksi as $key) {
                    $keranjang = json_decode($key->keranjang);
                    if (isset($keranjang->$kodebarang)) {
                        $total = $total + $keranjang->$kodebarang->jumlah;
                    }
                }
                return $total;
                break;
            
            default:
                return 'sesi tidak ada';
                break;
        }
    }
}