<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class KasirServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/Kasir/list.php';
        require_once app_path() . '/Helpers/Kasir/sistem.php';
        require_once app_path() . '/Helpers/Kasir/view.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
