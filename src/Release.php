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

final class Release
{
    private Tag $tag;
    private Changes $changes;

    private function __construct(
        Tag $tag,
        Changes $changes
    ) {
        $this->tag = $tag;
        $this->changes = $changes;
    }

    public static function create(
        Tag $tag,
        Changes $changes
    ): self {
        return new self(
            $tag,
            $changes,
        );
    }

    public function tag(): Tag
    {
        return $this->tag;
    }

    public function changes(): Changes
    {
        return $this->changes;
    }
}
