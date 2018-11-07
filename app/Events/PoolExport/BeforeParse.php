<?php

namespace App\Events\PoolExport;

use App\Console\Commands\ExportDomainsPool;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BeforeParse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var ExportDomainsPool
     */
    protected $command;

    /**
     * BeforeParse constructor.
     * @param $filePath
     * @param ExportDomainsPool $command
     */
    public function __construct($filePath, ExportDomainsPool $command)
    {
        $this->filePath = $filePath;
        $this->command = $command;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
