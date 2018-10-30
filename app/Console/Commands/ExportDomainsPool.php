<?php

namespace App\Console\Commands;

use App\Entities\History;
use App\Manager\DomainNameManager;
use App\Repository\DomainRepository;
use App\Utils\DomainsFileParser;
use Illuminate\Console\Command;
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
     * @var DomainNameManager
     */
    protected $domainManager;

    /**
     * @var int
     */
    protected $batchSize = 500;

    /**
     * Create a new command instance.
     *
     * @param DomainsFileParser $parser
     * @param DomainRepository $domainRepository
     * @param DomainNameManager $domainManager
     */
    public function __construct(DomainsFileParser $parser, DomainNameManager $domainManager)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->domainManager = $domainManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = $this->parser->findPoolFile('pool_downloads', new \DateTime());

        $historyRecord = new History();
        $historyRecord->setFileName($filePath)->setStatus('Start');

        if (!$filePath) {
            $this->warn('File was not found. The domains database was not updated');
            return false;
        }

        $this->info('Start parsing file ' . $filePath);
        $domainsParsed = $this->parser->parse($filePath);

        if ($domainsParsed) {
            $this->info($domainsParsed . ' domains have been parsed. Saving them to DB...');
            $this->saveToDB($domainsParsed);
        }
    }

    /**
     * @param $domains array
     */
    public function saveToDB($domains)
    {
        $progress = 0;
        $bar = $this->output->createProgressBar(count($domains));

        foreach ($domains as $domain) {
            $progress++;
            $bar->advance();

            $domainEntity = $this->domainManager->createFromArray($domain);
            EntityManager::persist($domainEntity);

            if (($i % $this->batchSize) == 0) {
                EntityManager::flush();
                EntityManager::clear();
            }
        }

        EntityManager::flush();
        EntityManager::clear();

        $bar->finish();
    }
}
