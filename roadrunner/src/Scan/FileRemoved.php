<?php declare(strict_types=1);

namespace App\Scan;

use App\Repository\FileRepository;
use App\Task\AnalyzeTask;
use InvalidArgumentException;
use Spiral\RoadRunner\Jobs\JobsInterface;

/**
 * Handles an event when a file is missing.
 */
class FileRemoved implements ScanInterface
{
    public function __construct(
        private FileRepository $file_repository,
        private JobsInterface $jobs
    ) {}

    public function process(string $path, ?string $hash = null): void
    {
        assert($hash === null, 'Hash must be null for FileRemoved event');

        $file = $this->file_repository->findByPath($path);
        // Update DB and set path to removed
        if ($file === null) {
            throw new InvalidArgumentException('File not found in DB: ' . $path);
        }

        $this->file_repository->updateRemovedByPath($path, date('Y-m-d H:i:s'));

        // Dispatch analyze job
        $queue = $this->jobs->connect('consumer');
        $task = $queue->create(
            AnalyzeTask::class,
            payload: json_encode(['file_id' => $file->id])
        );
        $queue->dispatch($task);
    }
}
