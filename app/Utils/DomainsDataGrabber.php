<?php

namespace App\Utils;

use App\Manager\DomainNameManager;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DomainsDataGrabber
 * @package App\Utils
 */
class DomainsDataGrabber
{
    /**
     * @var ClientInterface
     */
    protected $guzzleClient;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var DomainNameManager
     */
    protected $nameManager;

    /**
     * DomainsDataGrabber constructor.
     * @param ClientInterface $guzzleClient
     * @param Crawler $crawler
     * @param DomainNameManager $domainNameManager
     */
    public function __construct(ClientInterface $guzzleClient, Crawler $crawler, DomainNameManager $domainNameManager)
    {
        $this->guzzleClient = $guzzleClient;
        $this->crawler = $crawler;
        $this->nameManager = $domainNameManager;
    }

    public function getData($url)
    {
        $redisKey = $this->getCacheKey($url);
        $content = Cache::remember($redisKey, 10, function () use ($url) {
            $response = $this->guzzleClient->request('GET', $url);
            return $response->getBody()->getContents();
        });

        return $content;
    }

    public function addToCache($key, $content)
    {
        Cache::store('redis')->put($key, $content, 100);
    }

    public function getCacheKey($url)
    {
        $parts = parse_url($url);

        return $parts['query'];
    }

    public function isCached($url)
    {
        return false;
    }

    public function parsePage($pageHtml)
    {
        $this->crawler->add($pageHtml);

        $domains = [];
        $this->crawler
            ->filter('.listing .field_domain')
            ->each(function (Crawler $node, $i) use ($domains) {
                $domains[] = $this->parseDomainData($node->text());
            });

        return $domains;
    }

    /**
     * @param $string
     * @return array
     */
    public function parseDomainData($string)
    {
        return ['name' => $string];
    }
}