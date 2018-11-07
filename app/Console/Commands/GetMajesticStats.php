<?php

namespace App\Console\Commands;

use App\DomainName;
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
    public function handle(MajesticService $majecticService)
    {
        $domains = DomainName::where(['trust_flow' => null])
            ->orWhere(['citation_flow' => null])
            ->limit(10)
            ->get();

        foreach ($domains as $domain) {
            if (!$domain->trust_flow) {
                $trustFlow = $majecticService->getTrustFlow($domain->name);
                $domain->update(['trust_flow' => $trustFlow]);
            }
            if (!$domain->citationFlow) {
                $citationFlow = $majecticService->getCitationFlow($domain->name);
                $domain->update(['citation_flow' => $citationFlow]);
            }

            echo 'URL:' . $domain->name . ' TRUST:' . $trustFlow . ' CIT:' . $citationFlow . "\n\n";
        }
    }
}
