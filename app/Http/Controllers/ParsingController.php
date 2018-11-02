<?php

namespace App\Http\Controllers;

use App\Entities\History;
use App\Repository\HistoryRepository;
use App\Utils\DomainsFileParser;
use Illuminate\Support\Facades\Artisan;
use LaravelDoctrine\ORM\Facades\EntityManager;

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
}