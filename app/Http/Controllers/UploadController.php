<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadPoolFile;

class UploadController extends Controller
{
    public function upload()
    {
        DownloadPoolFile::dispatch()->onConnection('redis');
    }
}