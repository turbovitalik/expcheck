<?php

namespace App\Console\Commands;

use App\Entities\History;
use App\Manager\DomainNameManager;
use App\Repository\DomainRepository;
use App\Utils\DomainsFileParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $historyRecord->setFileName($filePath)->setStatus('Started')->setDescription('Started');
        EntityManager::persist($historyRecord);
        EntityManager::flush();

        if (!$filePath) {
            Log::error('File was not found. The domains database was not updated');
            return false;
        }

        Log::info('Start parsing file ' . $filePath);
        $domainsParsed = $this->parser->parse($filePath);

        if ($domainsParsed) {
            Log::info(count($domainsParsed) . ' domains have been parsed. Saving them to DB...');

            DB::table('domains')->truncate();
            $this->saveToDB($domainsParsed);

            $historyRecord->setStatus('Finished');
            $historyRecord->setDescription(count($domainsParsed) . ' domains has been exported');

            EntityManager::persist($historyRecord);
            EntityManager::flush();

            Log::info('Saved successfully');
        }

        return true;
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

            if (($progress % $this->batchSize) == 0) {
                EntityManager::flush();
                EntityManager::clear();
            }
        }

        EntityManager::flush();
        EntityManager::clear();

        $bar->finish();
    }
}
