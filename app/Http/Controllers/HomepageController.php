<?php

namespace App\Http\Controllers;

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
