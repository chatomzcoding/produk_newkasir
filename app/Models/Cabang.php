<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $guarded = [];

    public static function totalsupplier($cabang_id)
    {
        return Supplier::where('cabang_id',$cabang_id)->count();
    }
}
