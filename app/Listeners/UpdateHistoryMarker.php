<?php

namespace App\Listeners;

use App\Events\PoolExport\ExportSuccess;
use App\PoolImportHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateHistoryMarker
{
    /**
     * Handle the event.
     *
     * @param  ExportSuccess  $event
     * @return void
     */
    public function handle(ExportSuccess $event)
    {
        $command = $event->getCommand();
        $domains = $event->getDomains();

        PoolImportHistory::where(['timestamp' => $command->getTimestamp()])
            ->update([
                'status' => PoolImportHistory::STATUS_DONE,
                'description' => count($domains) . ' domains has been exported',
            ]);
    }
}
