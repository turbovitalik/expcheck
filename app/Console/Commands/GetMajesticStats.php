<?php

namespace App\Console\Commands;

use App\DomainName;
use App\Jobs\MajesticRequest;
use App\Service\MajesticService;
use Illuminate\Console\Command;

class GetMajesticStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'majestic:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stats from Majestic service and apply it domain names table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DomainName::where(['trust_flow' => null])
            ->orWhere(['citation_flow' => null])
            ->chunk(100, function($domains) {
                MajesticRequest::dispatch($domains)->onConnection('redis');
            });
    }
}
