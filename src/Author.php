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

final class Author
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidAuthor
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/\A(?=.{1,39}\z)[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*\z/', $value)) {
            throw InvalidAuthor::named($value);
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
