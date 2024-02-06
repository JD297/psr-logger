<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Logger\Handler;

use Psr\Clock\ClockInterface;
use Stringable;

final class HandlerParameters
{
    /**
     * @param array<mixed, mixed> $context
     */
    public function __construct(
        private readonly string $level,
        private readonly string|Stringable $message,
        private readonly ClockInterface $clock,
        private readonly array $context = []
    ) {
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getMessage(): Stringable|string
    {
        return $this->message;
    }

    public function getClock(): ClockInterface
    {
        return $this->clock;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
