<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $menu;
    public $title;
    public $chart;
    
    public function __construct($menu='beranda',$title='',$chart=FALSE)
    {
        $this->menu = $menu;
        $this->title = $title;
        $this->chart = $chart;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user   = Auth::user();
        return view('components.admin-layout', compact('user'));
    }
}
