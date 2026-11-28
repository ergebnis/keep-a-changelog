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

use Ergebnis\KeepAChangelog;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\ReleaseList
 *
 * @uses \Ergebnis\KeepAChangelog\Changes
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\InvalidReleaseList
 * @uses \Ergebnis\KeepAChangelog\Release
 * @uses \Ergebnis\KeepAChangelog\Tag
 * @uses \Ergebnis\KeepAChangelog\UnknownRelease
 */
final class ReleaseListTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    public function testEmptyReturnsReleaseList(): void
    {
        $releaseList = KeepAChangelog\ReleaseList::empty();

        self::assertSame([], $releaseList->toArray());
    }

    public function testCreateReturnsRelease(): void
    {
        $releaseList = KeepAChangelog\ReleaseList::create();

        self::assertSame([], $releaseList->toArray());
    }

    public function testCreateRejectsReleasesWithDuplicateTags(): void
    {
        $faker = self::faker();

        $tag = KeepAChangelog\Tag::fromString(self::faker()->semver());

        $values = \array_map(static function () use ($tag): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                $tag,
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $this->expectException(KeepAChangelog\InvalidReleaseList::class);

        KeepAChangelog\ReleaseList::create(...$values);
    }

    public function testCreateReturnsReleaseListWithReleases(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertSame($values, $releaseList->toArray());
    }

    public function testFirstReturnsNullWhenReleaseListIsEmpty(): void
    {
        $releaseList = KeepAChangelog\ReleaseList::empty();

        self::assertNull($releaseList->first());
    }

    public function testFirstReturnsReleaseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        $first = \reset($values);

        self::assertSame($first, $releaseList->first());
    }

    public function testLastReturnsNullWhenReleaseListIsEmpty(): void
    {
        $releaseList = KeepAChangelog\ReleaseList::empty();

        self::assertNull($releaseList->last());
    }

    public function testLastReturnsReleaseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        $last = \end($values);

        self::assertSame($last, $releaseList->last());
    }

    public function testReleaseForReturnsNullWhenReleaseListIsEmpty(): void
    {
        $tag = KeepAChangelog\Tag::fromString(self::faker()->semver());

        $releaseList = KeepAChangelog\ReleaseList::empty();

        self::assertNull($releaseList->releaseFor($tag));
    }

    public function testReleaseForReturnsNullWhenReleaseListDoesHaveReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $tag = KeepAChangelog\Tag::fromString($faker->semver());

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertNull($releaseList->releaseFor($tag));
    }

    public function testReleaseForReturnsReleaseWhenReleaseListHasReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        /** @var KeepAChangelog\Release $value */
        $value = $faker->randomElement($values);

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertSame($value, $releaseList->releaseFor($value->tag()));
    }

    public function testPreviousReleaseForRejectsTagWhenReleaseListIsEmpty(): void
    {
        $tag = KeepAChangelog\Tag::fromString(self::faker()->semver());

        $releaseList = KeepAChangelog\ReleaseList::empty();

        $this->expectException(KeepAChangelog\UnknownRelease::class);

        $releaseList->previousReleaseFor($tag);
    }

    public function testPreviousReleaseForRejectsTageWhenReleaseListDoesNotHaveReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $tag = KeepAChangelog\Tag::fromString($faker->semver());

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        $this->expectException(KeepAChangelog\UnknownRelease::class);

        $releaseList->previousReleaseFor($tag);
    }

    public function testPreviousReleaseForReturnsNullWhenReleaseListHasReleaseWithTagAndTagIsEarliest(): void
    {
        $values = [
            KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString('3.0.0'),
                KeepAChangelog\Changes::empty(),
            ),
            KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString('1.0.0'),
                KeepAChangelog\Changes::empty(),
            ),
            KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString('2.0.0'),
                KeepAChangelog\Changes::empty(),
            ),
        ];

        $tag = KeepAChangelog\Tag::fromString('1.0.0');

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertNull($releaseList->previousReleaseFor($tag));
    }

    public function testPreviousReleaseForReturnsPreviousReleaseWhenReleaseListHasReleaseWithPreviousRelease(): void
    {
        $previousRelease = KeepAChangelog\Release::create(
            KeepAChangelog\Tag::fromString('1.0.0'),
            KeepAChangelog\Changes::empty(),
        );

        $values = [
            KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString('3.0.0'),
                KeepAChangelog\Changes::empty(),
            ),
            $previousRelease,
            KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString('2.0.0'),
                KeepAChangelog\Changes::empty(),
            ),
        ];

        $tag = KeepAChangelog\Tag::fromString('2.0.0');

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertSame($previousRelease, $releaseList->previousReleaseFor($tag));
    }

    public function testSortedByTagAscendingReturnsReleaseListWithReleasesSortedByTagAscending(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        $mutated = $releaseList->sortedByTagAscending();

        self::assertNotSame($releaseList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (KeepAChangelog\Release $a, KeepAChangelog\Release $b): int {
            return $a->tag()->compare($b->tag());
        });

        self::assertEquals(KeepAChangelog\ReleaseList::create(...$sorted), $mutated);
    }

    public function testSortedByTagDescendingReturnsReleaseListWithReleasesSortedByTagDescending(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        $mutated = $releaseList->sortedByTagDescending();

        self::assertNotSame($releaseList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (KeepAChangelog\Release $a, KeepAChangelog\Release $b): int {
            return $b->tag()->compare($a->tag());
        });

        self::assertEquals(KeepAChangelog\ReleaseList::create(...$sorted), $mutated);
    }

    public function testIsEmptyReturnsFalseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($faker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = KeepAChangelog\ReleaseList::create(...$values);

        self::assertFalse($releaseList->isEmpty());
    }

    public function testIsEmptyReturnsRTrueWhenReleaseListIsEmpty(): void
    {
        $releaseList = KeepAChangelog\ReleaseList::empty();

        self::assertTrue($releaseList->isEmpty());
    }
}
