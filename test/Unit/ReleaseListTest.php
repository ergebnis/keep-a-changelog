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
use Ergebnis\KeepAChangelog\Tag;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\KeepAChangelog\UnknownRelease;
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

    public function testCreateRejectsReleasesWithDuplicateTags(): void
    {
        $faker = self::faker();

        $tag = Tag::fromString(self::faker()->semver());

        $values = \array_map(static function () use ($tag): Release {
            return Release::create(
                $tag,
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
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        self::assertSame($values, $releaseList->toArray());
    }

    public function testFirstReturnsNullWhenReleaseListIsEmpty(): void
    {
        $releaseList = ReleaseList::empty();

        self::assertNull($releaseList->first());
    }

    public function testFirstReturnsReleaseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        $first = \reset($values);

        self::assertSame($first, $releaseList->first());
    }

    public function testLastReturnsNullWhenReleaseListIsEmpty(): void
    {
        $releaseList = ReleaseList::empty();

        self::assertNull($releaseList->last());
    }

    public function testLastReturnsReleaseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        $last = \end($values);

        self::assertSame($last, $releaseList->last());
    }

    public function testReleaseForReturnsNullWhenReleaseListIsEmpty(): void
    {
        $tag = Tag::fromString(self::faker()->semver());

        $releaseList = ReleaseList::empty();

        self::assertNull($releaseList->releaseFor($tag));
    }

    public function testReleaseForReturnsNullWhenReleaseListDoesHaveReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $tag = Tag::fromString($faker->semver());

        $releaseList = ReleaseList::create(...$values);

        self::assertNull($releaseList->releaseFor($tag));
    }

    public function testReleaseForReturnsReleaseWhenReleaseListHasReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        /** @var Release $value */
        $value = $faker->randomElement($values);

        $releaseList = ReleaseList::create(...$values);

        self::assertSame($value, $releaseList->releaseFor($value->tag()));
    }

    public function testPreviousReleaseForRejectsTagWhenReleaseListIsEmpty(): void
    {
        $tag = Tag::fromString(self::faker()->semver());

        $releaseList = ReleaseList::empty();

        $this->expectException(UnknownRelease::class);

        $releaseList->previousReleaseFor($tag);
    }

    public function testPreviousReleaseForRejectsTageWhenReleaseListDoesNotHaveReleaseWithTag(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $tag = Tag::fromString($faker->semver());

        $releaseList = ReleaseList::create(...$values);

        $this->expectException(UnknownRelease::class);

        $releaseList->previousReleaseFor($tag);
    }

    public function testPreviousReleaseForReturnsNullWhenReleaseListHasReleaseWithTagAndTagIsEarliest(): void
    {
        $values = [
            Release::create(
                Tag::fromString('3.0.0'),
                Changes::empty(),
            ),
            Release::create(
                Tag::fromString('1.0.0'),
                Changes::empty(),
            ),
            Release::create(
                Tag::fromString('2.0.0'),
                Changes::empty(),
            ),
        ];

        $tag = Tag::fromString('1.0.0');

        $releaseList = ReleaseList::create(...$values);

        self::assertNull($releaseList->previousReleaseFor($tag));
    }

    public function testPreviousReleaseForReturnsPreviousReleaseWhenReleaseListHasReleaseWithPreviousRelease(): void
    {
        $previousRelease = Release::create(
            Tag::fromString('1.0.0'),
            Changes::empty(),
        );

        $values = [
            Release::create(
                Tag::fromString('3.0.0'),
                Changes::empty(),
            ),
            $previousRelease,
            Release::create(
                Tag::fromString('2.0.0'),
                Changes::empty(),
            ),
        ];

        $tag = Tag::fromString('2.0.0');

        $releaseList = ReleaseList::create(...$values);

        self::assertSame($previousRelease, $releaseList->previousReleaseFor($tag));
    }

    public function testSortedByTagAscendingReturnsReleaseListWithReleasesSortedByTagAscending(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        $mutated = $releaseList->sortedByTagAscending();

        self::assertNotSame($releaseList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (Release $a, Release $b): int {
            return $a->tag()->compare($b->tag());
        });

        self::assertEquals(ReleaseList::create(...$sorted), $mutated);
    }

    public function testSortedByTagDescendingReturnsReleaseListWithReleasesSortedByTagDescending(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        $mutated = $releaseList->sortedByTagDescending();

        self::assertNotSame($releaseList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (Release $a, Release $b): int {
            return $b->tag()->compare($a->tag());
        });

        self::assertEquals(ReleaseList::create(...$sorted), $mutated);
    }

    public function testIsEmptyReturnsFalseWhenReleaseListIsNotEmpty(): void
    {
        $faker = self::faker()->unique();

        $values = \array_map(static function () use ($faker): Release {
            return Release::create(
                Tag::fromString($faker->semver()),
                Changes::empty(),
            );
        }, \range(0, 4));

        $releaseList = ReleaseList::create(...$values);

        self::assertFalse($releaseList->isEmpty());
    }

    public function testIsEmptyReturnsRTrueWhenReleaseListIsEmpty(): void
    {
        $releaseList = ReleaseList::empty();

        self::assertTrue($releaseList->isEmpty());
    }
}
