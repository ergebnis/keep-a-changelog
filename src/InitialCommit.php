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

final class InitialCommit implements Reference
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidInitialCommit
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/\A[0-9a-f]{40}\z/', $value)) {
            throw InvalidInitialCommit::from($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
