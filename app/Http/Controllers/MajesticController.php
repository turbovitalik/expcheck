<?php

namespace App\Http\Controllers;

use App\DomainName;
use App\Service\MajesticService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;

class MajesticController extends Controller
{
    public function index()
    {
        $queueList = Redis::lrange('queues:default', 0, -1);

        $queueArray = [];
        foreach ($queueList as $item) {
            $jobData = json_decode($item);
            $queueArray[] = $jobData;
        }

        return view('majestic.tasks', [
            'queueList' => $queueArray,
        ]);
    }

    public function getChunk(MajesticService $majesticService)
    {
        $links = DomainName::limit(100)
            ->where(['trust_flow' => null])
            ->orWhere(['citation_flow' => null])
            ->get();

        $majesticService->updateMajesticStats($links);

        return redirect()->action('DomainController@index');
    }

    public function getAll()
    {
        Artisan::queue('majestic:stats', [])->onConnection('redis');

        return redirect()->action('DomainController@index')->with('status', 'Grabbing Majestic data started');
    }
}