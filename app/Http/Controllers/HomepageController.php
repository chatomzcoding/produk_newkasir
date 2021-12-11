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
        return redirect('login');
    }
}
