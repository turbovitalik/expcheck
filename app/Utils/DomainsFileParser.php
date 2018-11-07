<?php

namespace App\Utils;

use Illuminate\Contracts\Filesystem\Filesystem;

class DomainsFileParser
{
    /**
     * @var Filesystem
     */
    protected $storage;

    /**
     * DomainsFileParser constructor.
     * @param Filesystem $storage
     */
    public function __construct(Filesystem $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $content
     * @return array
     */
    public function parse($filePath)
    {
        $content = $this->loadFileContent($filePath);

        $lines = explode("\n", $content);

        $domainsArray = [];
        foreach ($lines as $line) {
            $domainData = $this->parseLine($line);
            if ($domainData) {
                $domainsArray[] = $domainData;
            }
        }

        return $domainsArray;
    }

    public function loadFileContent($filePath)
    {
        $contents = $this->storage->get($filePath);

        return $contents;
    }

    /**
     * @param $string
     * @return array|bool
     */
    public function parseLine($string)
    {
        $parts = explode(',', $string);

        if (count($parts) < 2) {
            return false;
        }

        $domainData = [
            'name' => $parts[0],
            'expires_at' => $this->parseExpiration($parts[1]),
        ];

        return $domainData;
    }

    /**
     * @param string $string
     * @return \DateTime
     */
    public function parseExpiration(string $string)
    {
        $datetime = \DateTime::createFromFormat("m/d/Y h:i:s a", $string);

        return $datetime;
    }

    /**
     * @param $dir
     * @param \DateTime $datetime
     * @return bool|string
     */
    public function findPoolFile($dir, \DateTime $datetime)
    {
        $poolFileName = $datetime->format('m_d_Y') . '-pool/list.txt';
        $poolFilePath = $dir . '/' . $poolFileName;

        if (!$this->storage->exists($poolFilePath)) {
            return false;
        }

        return $poolFilePath;
    }
}