<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Barang;
use App\Models\Galeri;
use App\Models\Infowebsite;
use App\Models\Kategori;
use App\Models\Kategoriartikel;
use App\Models\Lapak;
use App\Models\Lapor;
use App\Models\Potensi;
use App\Models\Potensisub;
use App\Models\Produk;
use App\Models\Profil;
use App\Models\Slider;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class HomepageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('visitorhits');
    }
    
    public function index()
    {
        $barang     = Barang::all();
        $keranjang  = [];
        foreach ($barang as $item) {
            $detail     = [
                'nama' => $item->nama_barang,
                'harga_beli' => $item->harga_beli,
                'harga_jual' => $item->harga_jual,
            ];
            $keranjang[$item->kode_barang] = $detail;
        }

        print_r($keranjang);

        // ubah barang 1;
        $keranjang['KD-1638452520'] = [
            'nama' => $keranjang['KD-1638452520']['nama'],
            'harga_beli' => 30000,
            'harga_jual' => 38000,
        ];

        print_r($keranjang);

        die();
    }
}
