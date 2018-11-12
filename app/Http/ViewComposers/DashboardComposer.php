<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redis;

class DashboardComposer
{
    public function compose(View $view)
    {
        $queue = Redis::lrange('queues:default', 0, -1);

        $view->with('queueNumber', count($queue));
    }
}
