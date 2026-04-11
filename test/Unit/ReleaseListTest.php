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

use Ergebnis\KeepAChangelog\Changes;
use Ergebnis\KeepAChangelog\InvalidReleaseList;
use Ergebnis\KeepAChangelog\Release;
use Ergebnis\KeepAChangelog\ReleaseList;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\ReleaseList
 *
 * @uses \Ergebnis\KeepAChangelog\Changes
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\InvalidReleaseList
 * @uses \Ergebnis\KeepAChangelog\Release
 */
final class ReleaseListTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testEmptyReturnsReleaseList(): void
    {
        $releaseList = ReleaseList::empty();

        self::assertSame([], $releaseList->toArray());
    }

    public function testCreateReturnsRelease(): void
    {
        $releaseList = ReleaseList::create();

        self::assertSame([], $releaseList->toArray());
    }

    public function testCreateRejectsReleasesWithDuplicateVersions(): void
    {
        $faker = self::faker();

        $version = Version\Version::fromString(self::faker()->semver());

        $values = \array_map(static function () use ($version): Release {
            return Release::create(
                $version,
                Changes::empty(),
            );
        }, \range(0, 4));

        $this->expectException(InvalidReleaseList::class);

        ReleaseList::create(...$values);
    }

    public function testCreateReturnsReleaseListWithReleases(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Version\Version::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        $sorted = $values;

        \usort($sorted, static function (Release $a, Release $b): int {
            return $a->version()->compare($b->version());
        });

        self::assertSame($sorted, $releaseList->toArray());
    }
}
