<?php

declare(strict_types=1);

namespace App\Analyze\Image;

use App\Analyze\AnalyzeInterface;
use App\Model\File;
use Psr\Log\LoggerInterface;

class Png implements AnalyzeInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function analyze(File $file): string
    {
        $this->logger->info(__CLASS__ . ' analyzer called');

        $file_contents = file_get_contents($file->path);

        $file->transcript = $file_contents;

        return $file_contents;
    }
}
