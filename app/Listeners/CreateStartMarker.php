<?php

namespace App\Listeners;

use App\Events\PoolExport\BeforeParse;
use App\PoolImportHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateStartMarker
{
    /**
     * Handle the event.
     *
     * @param  BeforeParse  $event
     * @return void
     */
    public function handle(BeforeParse $event)
    {
        $recordData = [
            'file_name' => $event->getFilePath(),
            'status' => PoolImportHistory::STATUS_IN_PROGRESS,
            'description' => 'Export has been started',
            'timestamp' => $event->getCommand()->getTimestamp(),
        ];

        PoolImportHistory::create($recordData);
    }
}
