<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Logger;

use Jd297\Psr\Logger\Handler\HandlerInterface;
use Jd297\Psr\Logger\Handler\HandlerParameters;
use Psr\Clock\ClockInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Stringable;

class Logger extends AbstractLogger
{
    /**
     * @param array<HandlerInterface> $handlers
     */
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly array $handlers = []
    ) {
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param array<mixed, mixed> $context
     *
     *
     * @throws InvalidArgumentException
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!in_array($level, [
            LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::DEBUG, LogLevel::INFO, LogLevel::EMERGENCY, LogLevel::ERROR,
            LogLevel::NOTICE, LogLevel::WARNING,
        ], true)) {
            throw new InvalidArgumentException('An invalid log level was given!');
        }

        $message = $this->interpolate($message, $context);

        foreach ($this->handlers as $handler) {
            $handler->handle(new HandlerParameters($level, $message, $this->clock, $context));
        }
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param array<mixed, mixed> $context
     *
     */
    protected function interpolate(string|Stringable $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{'.$key.'}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr(strval($message), $replace) ?: '';
    }
}
