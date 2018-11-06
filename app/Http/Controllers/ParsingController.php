<?php

namespace App\Http\Controllers;

use App\Manager\DomainNameManager;
use App\Repository\HistoryRepository;
use App\Utils\DomainsDataGrabber;
use App\Utils\DomainsFileParser;
use Illuminate\Support\Facades\Artisan;

class ParsingController extends Controller
{
    public function info(DomainsFileParser $fileParser, HistoryRepository $historyRepository)
    {
        $fileName = $fileParser->findPoolFile('pool_downloads', new \DateTime());

        $history = $historyRepository->findBy([], ['createdAt' => 'DESC']);

        return view('parsing.info', ['fileName' => $fileName, 'history' => $history]);
    }

    public function start()
    {
        Artisan::queue('pool:export', [])->onConnection('redis');

        return redirect()->action('ParsingController@info')->with('status', 'Export has been scheduled');
    }

    public function grab(DomainsDataGrabber $grabber, DomainNameManager $domainNameManager)
    {
        $url = 'https://www.expireddomains.net/godaddy-closeout-domains/?start=25';
        $content = $grabber->getData($url);

        $domains = $grabber->parsePage($content);

        foreach ($domains as $domain) {
            $domainEntity = $domainNameManager->createFromArray($domain['name'], $domain);
        }

    }
}