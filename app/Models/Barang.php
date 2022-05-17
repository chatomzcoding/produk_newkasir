<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $guarded = [];

    public static function cabangPerKategori($kategori)
    {
        return Barang::where('kategori_id',$kategori)->orderBy('nama_barang','ASC')->get();
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Kategori::class,'satuan_id');
    }
}
