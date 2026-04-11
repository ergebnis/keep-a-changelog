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

final class Description
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidDescription
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/\A(?!\s)[^\r\n]+(?<!\s)\z/', $value)) {
            throw InvalidDescription::from($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
