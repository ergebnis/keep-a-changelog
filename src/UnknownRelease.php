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

final class UnknownRelease extends \InvalidArgumentException
{
    public static function named(Tag $tag): self
    {
        return new self(\sprintf(
            'Tag "%s" does not refer to a known release.',
            $tag->toString(),
        ));
    }
}
