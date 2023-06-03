<?php declare(strict_types=1);

namespace App\Scan;

use App\Logger\NullLogger;
use Psr\Log\LoggerInterface;
use Test\TestCase;
use Test\Traits\HasJobsMock;

class DirectoryScanTest extends TestCase
{
    use HasJobsMock;

    public function testItWalksADirectory(): void
    {
        $path = sys_get_temp_dir() . '/' . uniqid('DirectoryScanTest-');
        mkdir($path, 0777, true);
        touch($path . '/file-1.txt');
        touch($path . '/file-2.txt');
        mkdir($path . '/subdir-1');
        touch($path . '/subdir-1/file-3.txt');

        $this->injectQueueExpectation(3);
        $this->container->add(LoggerInterface::class, new NullLogger());
        $directory_scan = $this->container->get(DirectoryScan::class);

        $this->assertNull(
            $directory_scan->process($path)
        );
    }

    public function testItDoesNothingIfPathDoesNotExist()
    {
        $this->container->add(LoggerInterface::class, new NullLogger());
        $directory_scan = $this->container->get(DirectoryScan::class);

        $this->assertNull(
            $directory_scan->process(sys_get_temp_dir() . '/' . uniqid('DirectoryScanTest-'))
        );
    }

    public function testItDoesNothingIfPathIsAFile()
    {
        $path = sys_get_temp_dir() . '/' . uniqid('DirectoryScanTest-');
        touch($path);

        $this->container->add(LoggerInterface::class, new NullLogger());
        $directory_scan = $this->container->get(DirectoryScan::class);

        $this->assertNull(
            $directory_scan->process($path)
        );
    }
}
