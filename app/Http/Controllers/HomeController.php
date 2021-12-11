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
            default:
                # code...
                break;
        }
    }
}
