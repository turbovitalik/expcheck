<?php

namespace App\Console\Commands;

use App\DomainName;
use App\Events\PoolExport\BeforeParse;
use App\Events\PoolExport\ExportSuccess;
use App\Utils\DomainsFileParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @var int
     */
    protected $batchSize = 500;

    /**
     * @var integer
     */
    protected $timestamp;

    /**
     * ExportDomainsPool constructor.
     * @param DomainsFileParser $parser
     */
    public function __construct(DomainsFileParser $parser)
    {
        parent::__construct();
        $this->parser = $parser;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle()
    {
        $this->setTimestamp();
        $filePath = $this->parser->findPoolFile('pool_downloads', new \DateTime());

        event(new BeforeParse($filePath, $this));

        if (!$filePath) {
            Log::error('File was not found. The domains database was not updated');
            return false;
        }

        $domainsParsed = $this->parser->parse($filePath);

        if ($domainsParsed) {
            DB::table('domains')->truncate();
            $this->saveToDB($domainsParsed);
            event(new ExportSuccess($domainsParsed, $this));
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

            $domainData['source'] = DomainName::SOURCE_POOL;
            $domainData['created_at'] = new \DateTime();
            $domainData['tld'] = $this->getTld($domainData['name']);
            $domainName = new DomainName($domainData);

            Log::info('Saved ' . $domainName->name . ' ' . date_format($domainName->created_at, 'Y-m-d H:i:s'));

            $insertData[] = $domainData;

            if (($progress % $this->batchSize) == 0) {
                unset($insertData);
            }
        }

        DomainName::insert($insertData);
        $bar->finish();
    }

    public function setTimestamp()
    {
        $this->timestamp = time();
    }

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getTld($name)
    {
        $nameParts = explode('.', $name);

        return end($nameParts);
    }
}
