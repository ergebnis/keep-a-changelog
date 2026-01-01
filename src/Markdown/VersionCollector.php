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

namespace Ergebnis\KeepAChangelog\Markdown;

use Ergebnis\Version;

final class VersionCollector
{
    /**
     * @return list<Version\Version>
     */
    public function collect(string $content): array
    {
        $versions = [];

        foreach (\explode("\n", $content) as $line) {
            if (0 === \preg_match('/^## \[`(?P<version>[^`]+)`]/', $line, $matches)) {
                continue;
            }

            try {
                $version = Version\Version::fromString($matches['version']);
            } catch (Version\Exception\InvalidVersion $exception) {
                continue;
            }

            $versions[] = $version;
        }

        \usort($versions, static function (Version\Version $a, Version\Version $b): int {
            return $a->compare($b);
        });

        return $versions;
    }
}
