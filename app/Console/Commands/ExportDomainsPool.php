<?php

namespace App\Console\Commands;

use App\Entities\History;
use App\Manager\DomainNameManager;
use App\Manager\HistoryManager;
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
     * @var HistoryManager
     */
    protected $historyManager;

    /**
     * @var int
     */
    protected $batchSize = 500;

    /**
     * ExportDomainsPool constructor.
     * @param DomainsFileParser $parser
     * @param DomainNameManager $domainManager
     * @param HistoryManager $historyManager
     */
    public function __construct(DomainsFileParser $parser, DomainNameManager $domainManager, HistoryManager $historyManager)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->domainManager = $domainManager;
        $this->historyManager = $historyManager;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle()
    {
        $filePath = $this->parser->findPoolFile('pool_downloads', new \DateTime());

        $historyRecord = $this->historyManager->createHistoryRecord($filePath, History::STATUS_IN_PROGRESS, 'Export has been started');
        $this->historyManager->save($historyRecord);

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

            $historyRecord->setStatus(History::STATUS_DONE);
            $historyRecord->setDescription(count($domainsParsed) . ' domains has been exported');

            //todo: move this to another place
            EntityManager::merge($historyRecord);
            EntityManager::flush();

            Log::info('Saved successfully');
        }

        return true;
    }

    /**
     * todo: cover this with tests
     * @param $domains
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function saveToDB($domains)
    {
        $progress = 0;
        $bar = $this->output->createProgressBar(count($domains));

        foreach ($domains as $domainData) {
            $progress++;
            $bar->advance();

            $domainName = $domainData['name'];
            unset($domainData['name']);
            $domainEntity = $this->domainManager->createFromArray($domainName, $domainData);

            Log::info('Saved ' . $domainEntity->getName() . ' ' . date_format($domainEntity->getCreatedAt(), 'Y-m-d H:i:s'));
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
