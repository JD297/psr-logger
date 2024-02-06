<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Logger\Test;

use Jd297\Psr\Clock\SystemClock;
use Jd297\Psr\Logger\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

class LoggerTest extends TestCase
{
    public function testLoggerWithoutHandlers(): void
    {
        $logger = new Logger(new SystemClock(), []);

        $this->expectNotToPerformAssertions();

        $logger->log(LogLevel::INFO, 'Logging without a handler does nothing.');
    }

    public function testLoggerLogMethodThrowsPsrLogInvalidArgumentException(): void
    {
        $logger = new Logger(new SystemClock(), []);

        $this->expectException(InvalidArgumentException::class);

        $logger->log('custom', 'Using custom log levels is not allowed!');
    }
}
