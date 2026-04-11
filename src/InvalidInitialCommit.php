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

final class InvalidInitialCommit extends \InvalidArgumentException
{
    public static function from(string $value): self
    {
        return new self(\sprintf(
            'Value "%s" is not a valid initial commit.',
            $value,
        ));
    }
}
