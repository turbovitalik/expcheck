<?php

namespace App\Providers;

use App\Entities\DomainName;
use App\Entities\History;
use App\Repository\DoctrineDomainRepository;
use App\Repository\DomainRepository;
use App\Repository\HistoryRepository;
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

        $this->app->bind(HistoryRepository::class, function($app) {
            return new HistoryRepository(
                EntityManager::getRepository(History::class)
            );
        });
    }
}
