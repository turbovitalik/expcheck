<?php

namespace App\Http\Controllers;

use App\PoolImportHistory;
use App\Utils\DomainsFileParser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;

class ParsingController extends Controller
{
    public function info(DomainsFileParser $fileParser)
    {
        $fileName = $fileParser->findPoolFile('pool_downloads', new \DateTime());

        $history = PoolImportHistory::orderBy('created_at', 'desc')
            ->get();

        $queue = Redis::lrange('queues:default', 0, -1);

        return view('parsing.info', ['fileName' => $fileName, 'history' => $history, 'queue' => $queue]);
    }

    public function start()
    {
        Artisan::queue('pool:export', [])->onConnection('redis');

        return redirect()->action('ParsingController@info')->with('status', 'Export has been scheduled');
    }
}