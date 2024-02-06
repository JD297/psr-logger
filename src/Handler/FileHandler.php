<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Logger\Handler;

use DateTimeInterface;

class FileHandler implements HandlerInterface
{
    public function __construct(
        private readonly string $filename,
        private readonly int $permissions = 0777
    ) {
    }

    /**
     * Logs to a file in the following schema:
     * [TIME] [LEVEL]: MESSAGE JSON_CONTEXT EOL
     *
     *
     */
    public function handle(HandlerParameters $parameters): void
    {
        $message = sprintf(
            '[%s] [%s]: %s %s%s',
            $parameters->getClock()->now()->format(DateTimeInterface::ATOM),
            strtoupper($parameters->getLevel()),
            $parameters->getMessage(),
            json_encode($parameters->getContext()),
            PHP_EOL
        );

        $logDirectory = dirname($this->filename);

        if (!file_exists($logDirectory)) {
            mkdir(dirname($this->filename), $this->permissions, true);
        }

        file_put_contents($this->filename, $message, FILE_APPEND|LOCK_EX);
    }
}
