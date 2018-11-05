<?php

namespace App\Utils;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

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
     * DomainsDataGrabber constructor.
     * @param ClientInterface $guzzleClient
     * @param Crawler $crawler
     */
    public function __construct(ClientInterface $guzzleClient, Crawler $crawler)
    {
        $this->guzzleClient = $guzzleClient;
        $this->crawler = $crawler;
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

        $this->crawler
            ->filter('.listing .field_domain')
            ->each(function (Crawler $node, $i) {
                $domainData = $this->parseDomainData($node->text());
                var_dump($domainData);
            });
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