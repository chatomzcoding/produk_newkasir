<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $guarded = [];

    public static function cabangPerKategori($cabang_id,$kategori)
    {
        return Barang::where('cabang_id',$cabang_id)->where('kategori_id',$kategori)->orderBy('nama_barang','ASC')->get();
    }
}
