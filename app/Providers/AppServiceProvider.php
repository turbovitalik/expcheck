<?php

namespace App\Providers;

use App\Entities\DomainName;
use App\Repository\DoctrineDomainRepository;
use App\Repository\DomainRepository;
use EntityManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(DomainRepository::class, function($app) {
           return new DoctrineDomainRepository(
               EntityManager::getRepository(DomainName::class), $app['em']
           );
        });
    }
}
