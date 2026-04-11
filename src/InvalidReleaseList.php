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

use Ergebnis\Version;

final class InvalidReleaseList extends \InvalidArgumentException
{
    public static function withDuplicateVersions(Version\Version ...$versions): self
    {
        return new self(\sprintf(
            'Release list contains more than one release with version(s) "%s".',
            \implode('", "', \array_map(static function (Version\Version $version): string {
                return $version->toString();
            }, $versions)),
        ));
    }
}
