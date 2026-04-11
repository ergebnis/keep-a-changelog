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

final class Section
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function added(): self
    {
        return new self('added');
    }

    public static function changed(): self
    {
        return new self('changed');
    }

    public static function deprecated(): self
    {
        return new self('deprecated');
    }

    public static function removed(): self
    {
        return new self('removed');
    }

    public static function fixed(): self
    {
        return new self('fixed');
    }

    public static function security(): self
    {
        return new self('security');
    }

    /**
     * @throws UnknownSection
     */
    public static function fromString(string $value): self
    {
        $values = [
            'added',
            'changed',
            'deprecated',
            'removed',
            'fixed',
            'security',
        ];

        if (!\in_array($value, $values, true)) {
            throw UnknownSection::named($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
