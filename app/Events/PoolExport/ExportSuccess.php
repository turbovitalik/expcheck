<?php

namespace App\Events\PoolExport;

use App\Console\Commands\ExportDomainsPool;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ExportSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    protected $domains;

    /**
     * @var ExportDomainsPool
     */
    protected $command;

    /**
     * ExportSuccess constructor.
     * @param $domains
     * @param ExportDomainsPool $command
     */
    public function __construct($domains, ExportDomainsPool $command)
    {
        $this->domains = $domains;
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

    /**
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @return ExportDomainsPool
     */
    public function getCommand()
    {
        return $this->command;
    }
}
