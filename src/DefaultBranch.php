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

final class DefaultBranch
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function main(): self
    {
        return new self('main');
    }

    /**
     * @throws InvalidDefaultBranch
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/\A[a-zA-Z0-9_][a-zA-Z0-9._\/-]{0,254}\z/', $value)) {
            throw InvalidDefaultBranch::named($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
