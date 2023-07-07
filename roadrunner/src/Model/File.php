<?php

declare(strict_types=1);

namespace App\Model;

use stdClass;

class File
{
    public int $id;
    public string $hash;
    public string $path;
    public ?string $name = null;
    public ?int $size = null;
    public ?string $mime = null;
    public ?string $date = null;
    public ?float $lat = null;
    public ?float $lon = null;
    public ?stdClass $metadata = null;
    public ?string $transcript = null;
    public int $scan_version = 0;
    public ?string $scanned_at = null;
    public ?string $analyzed_at = null;
    public string $created_at;
    public string $updated_at;
    public ?string $removed_at = null;

    public function __construct(string $hash, string $path) {
        $this->hash = $hash;
        $this->path = $path;
    }

    public function isRemoved(): bool
    {
        return $this->removed_at !== null;
    }
}
