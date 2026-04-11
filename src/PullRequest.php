<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/keep-a-changelog
 */

namespace Ergebnis\KeepAChangelog;

final class PullRequest
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidPullRequest
     */
    public static function fromInt(int $value): self
    {
        if (1 > $value) {
            throw InvalidPullRequest::from($value);
        }

        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
