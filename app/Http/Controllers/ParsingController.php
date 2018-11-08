<?php

namespace App\Http\Controllers;

use App\DomainName;
use App\PoolImportHistory;
use App\Service\MajesticService;
use App\Utils\DomainsFileParser;
use Illuminate\Support\Facades\Artisan;

class ParsingController extends Controller
{
    public function info(DomainsFileParser $fileParser)
    {
        $fileName = $fileParser->findPoolFile('pool_downloads', new \DateTime());

        $history = PoolImportHistory::orderBy('created_at', 'desc')
            ->get();

        return view('parsing.info', ['fileName' => $fileName, 'history' => $history]);
    }

    public function start()
    {
        Artisan::queue('pool:export', [])->onConnection('redis');

        return redirect()->action('ParsingController@info')->with('status', 'Export has been scheduled');
    }

    public function grab(MajesticService $majesticService)
    {
        $links = DomainName::limit(100)
            ->get();

        $majesticService->updateMajesticStats($links);

    }
}