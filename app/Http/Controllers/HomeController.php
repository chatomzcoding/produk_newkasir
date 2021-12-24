<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $menu = 'beranda';
        $user   = Auth::user();
        switch ($user->level) {
            case 'superadmin':
                return view('superadmin.dashboard', compact('menu'));
                break;
            case 'client':
                return view('client.dashboard', compact('menu'));
                break;
            case 'cabang':
                return view('cabang.dashboard', compact('menu'));
                break;
            case 'kasir':
                return view('kasir.dashboard', compact('menu'));
                break;
            default:
                # code...
                break;
        }
    }
}
