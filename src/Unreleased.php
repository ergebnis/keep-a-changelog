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

final class Unreleased
{
    private Changes $changes;

    private function __construct(Changes $changes)
    {
        $this->changes = $changes;
    }

    public static function empty(): self
    {
        return new self(Changes::empty());
    }

    public static function create(Changes $changes): self
    {
        return new self($changes);
    }

    public function changes(): Changes
    {
        return $this->changes;
    }
}
