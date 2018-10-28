<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadPoolFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pool:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads zip-file with domains pool';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'http://pool.com/Downloads/PoolDeletingDomainsList.zip';
        $contents = file_get_contents($url);
        $name = 'uploads/pool.zip';
        Storage::disk('local')->put($name, $contents);
    }
}
