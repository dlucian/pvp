<?php declare(strict_types=1);

namespace App\Scan;

use App\Model\File;
use App\Repository\FileRepository;
use Test\TestCase;
use Test\Traits\HasJobsMock;

class FileUpdatedTest extends TestCase
{
    use HasJobsMock;

    public function testItUpdatesTheDatasbaseWithTheNewChecksum(): void
    {
        // Create a file in the database only
        $path = tempnam(sys_get_temp_dir(), 'pvp-');
        file_put_contents($path, 'some content ' . uniqid('content-', true));
        $hash = hash_file('sha256', $path);
        $file = new File(uniqid('hash-'), $path);
        $file->removed_at = date('Y-m-d H:i:s');
        /** @var FileRepository::class */
        $file_repository = $this->container->get(FileRepository::class);
        $file_repository->create($file);

        // Expect analyze job to be dispatched
        $this->injectQueueExpectation(1);

        $file_removed = $this->container->get(FileUpdated::class);

        $this->assertNull(
            // Same file path, but completely different hash
            $file_removed->process($path, $hash)
        );

        // Check that the file was set to removed
        $file = $file_repository->findByPath($path);
        $this->assertNotNull($file);
        // Updated hash
        $this->assertSame($hash, $file->hash);
        $this->assertFalse($file->isRemoved());
    }

    public function testItThrowsIfHashIsNull(): void
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessage('Hash must be set for FileUpdated event');

        $file_removed = $this->container->get(FileUpdated::class);
        $file_removed->process('/some/path', null);
    }
}
