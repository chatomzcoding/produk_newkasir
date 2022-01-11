<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $guarded = [];

    public static function kategori($cabang_id)
    {
        return Kategori::where('cabang_id',$cabang_id)->where('label','kategori')->orderBy('nama','ASC')->get();
    }
    public static function satuan($cabang_id)
    {
        return Kategori::where('cabang_id',$cabang_id)->where('label','satuan')->orderBy('nama','ASC')->get();
    }
    public static function produsen($cabang_id)
    {
        return Kategori::where('cabang_id',$cabang_id)->where('label','produsen')->orderBy('nama','ASC')->get();
    }
}
