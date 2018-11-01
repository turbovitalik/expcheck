<?php

namespace App\Http\Controllers;

use App\Repository\HistoryRepository;
use App\Utils\DomainsFileParser;
use Illuminate\Support\Facades\Artisan;

class ParsingController extends Controller
{
    public function info(DomainsFileParser $fileParser, HistoryRepository $historyRepository)
    {
        $fileName = $fileParser->findPoolFile('pool_downloads', new \DateTime());

        $history = $historyRepository->findAll();

        return view('parsing.info', ['fileName' => $fileName, 'history' => $history]);
    }

    public function start()
    {
        Artisan::call('pool:export', []);

        $status = Artisan::output() ? 'Success' : 'Failure! Something went wrong';

        return redirect()->action('ParsingController@info')->with('status', $status);
    }
}