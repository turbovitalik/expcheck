<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer(
            'layouts.app', 'App\Http\ViewComposers\DashboardComposer'
        );
    }
}
