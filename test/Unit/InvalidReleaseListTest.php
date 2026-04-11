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

namespace Ergebnis\KeepAChangelog\Test\Unit;

use Ergebnis\KeepAChangelog\InvalidReleaseList;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\InvalidReleaseList
 */
final class InvalidReleaseListTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testWithDuplicateVersionsReturnsException(): void
    {
        $faker = self::faker()->unique();

        $versions = \array_map(static function () use ($faker): Version\Version {
            return Version\Version::fromString($faker->semver());
        }, \range(0, 2));

        $exception = InvalidReleaseList::withDuplicateVersions(...$versions);

        $expected = \sprintf(
            'Release list contains more than one release with version(s) "%s".',
            \implode('", "', \array_map(static function (Version\Version $version): string {
                return $version->toString();
            }, $versions)),
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
