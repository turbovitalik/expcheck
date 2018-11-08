<?php

namespace App\Jobs;

use App\Service\MajesticService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MajesticRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domainsCollection;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domainsCollection)
    {
        $this->domainsCollection = $domainsCollection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MajesticService $majesticService)
    {
        $majesticService->updateMajesticStats($this->domainsCollection);
    }
}
