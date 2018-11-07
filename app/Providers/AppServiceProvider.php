<?php

namespace App\Providers;

use App\Entities\DomainName;
use App\Entities\History;
use App\Repository\DoctrineDomainRepository;
use App\Repository\DomainRepository;
use App\Repository\HistoryRepository;
use EntityManager;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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
        Queue::before(function (JobProcessing $event) {
            Log::info('Start processing job' . $event->job->getJobId() . ' ...');
        });

        Queue::after(function (JobProcessed $event) {
            Log::info('Job ' . $event->job->getName() . ' processed');
        });
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

        $this->app->bind(
            'GuzzleHttp\ClientInterface',
            'GuzzleHttp\Client'
        );
    }
}
