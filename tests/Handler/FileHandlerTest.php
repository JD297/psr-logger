<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Logger\Test\Handler;

use FilesystemIterator;
use Jd297\Psr\Clock\StaticClock;
use Jd297\Psr\Logger\Handler\FileHandler;
use Jd297\Psr\Logger\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class FileHandlerTest extends TestCase
{
    private const VAR_DIRECTORY = __DIR__.'/var';

    public function setUp(): void
    {
        $this->removeVarDirectory();
    }

    public function tearDown(): void
    {
        $this->removeVarDirectory();
    }

    public function testFileLoggerLogMethodLogsFile(): void
    {
        $filename = self::VAR_DIRECTORY.'/log/test.log';

        $logger = new Logger(new StaticClock(), [new FileHandler($filename)]);

        $logger->log(LogLevel::INFO, '');

        $this->assertFileExists($filename);
        $this->assertFileIsReadable($filename);
        $this->assertFileIsWritable($filename);
    }

    public function testFileLoggerMessageHashSum(): void
    {
        $filename = self::VAR_DIRECTORY.'/log/test.log';

        $logger = new Logger(new StaticClock('2024-01-15 17:30:40'), [new FileHandler($filename)]);

        $logger->log(LogLevel::INFO, 'User "{username}" created with email "{email}".', [
            'username' => 'john',
            'email' => 'john.doe@local.local',
        ]);

        $logger->log(LogLevel::DEBUG, 'User "{username}" created with email "{email}".', [
            'username' => 'max',
            'email' => 'max.musterman@local.local',
        ]);

        $fileResult = <<<FILE_RESULT
[2024-01-15T17:30:40+00:00] [INFO]: User "john" created with email "john.doe@local.local". {"username":"john","email":"john.doe@local.local"}
[2024-01-15T17:30:40+00:00] [DEBUG]: User "max" created with email "max.musterman@local.local". {"username":"max","email":"max.musterman@local.local"}

FILE_RESULT;


        $this->assertFileExists($filename);
        $this->assertFileIsReadable($filename);
        $this->assertFileIsWritable($filename);
        $this->assertStringEqualsFile($filename, $fileResult);
    }

    private static function removeVarDirectory(): void
    {
        if (!file_exists(self::VAR_DIRECTORY)) {
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::VAR_DIRECTORY, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDir()) {
                rmdir($fileInfo->getRealPath());
            } else {
                unlink($fileInfo->getRealPath());
            }
        }

        rmdir(self::VAR_DIRECTORY);
    }
}
