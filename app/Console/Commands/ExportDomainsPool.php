<?php

namespace App\Console\Commands;

use App\Manager\DomainNameManager;
use App\Repository\DomainRepository;
use App\Utils\DomainsFileParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use LaravelDoctrine\ORM\Facades\EntityManager;

class ExportDomainsPool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pool:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts lines from .txt file into database entries';

    /**
     * @var DomainsFileParser
     */
    protected $parser;

    /**
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * @var DomainNameManager
     */
    protected $domainManager;

    /**
     * Create a new command instance.
     *
     * @param DomainsFileParser $parser
     * @param DomainRepository $domainRepository
     * @param DomainNameManager $domainManager
     */
    public function __construct(DomainsFileParser $parser, DomainRepository $domainRepository, DomainNameManager $domainManager)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->domainRepository = $domainRepository;
        $this->domainManager = $domainManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $downloadsDir = Storage::disk('local')->path('pool_downloads');
        $filePath = $this->parser->findPoolFile($downloadsDir, new \DateTime());

        if (!$filePath) {
            $this->info('File was not found');
        }

        $domainsParsed = $this->parser->parse($filePath);

        $bar = $this->output->createProgressBar(count($domainsParsed));

        $batchSize = 500;

        $i = 0;
        foreach ($domainsParsed as $domain) {
            $i++;
            $bar->advance();

            $domainEntity = $this->domainManager->createFromArray($domain);
            EntityManager::persist($domainEntity);

            if (($i % $batchSize) == 0) {
                EntityManager::flush();
                EntityManager::clear();
            }
        }

        EntityManager::flush();
        EntityManager::clear();

        $bar->finish();
    }
}
