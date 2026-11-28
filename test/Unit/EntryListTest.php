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
 * @covers \Ergebnis\KeepAChangelog\EntryList
 *
 * @uses \Ergebnis\KeepAChangelog\Author
 * @uses \Ergebnis\KeepAChangelog\Description
 * @uses \Ergebnis\KeepAChangelog\Entry
 * @uses \Ergebnis\KeepAChangelog\Notes
 * @uses \Ergebnis\KeepAChangelog\PullRequest
 */
final class EntryListTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    public function testEmptyReturnsEntryList(): void
    {
        $entryList = KeepAChangelog\EntryList::empty();

        self::assertSame([], $entryList->toArray());
    }

    public function testCreateReturnsEntryList(): void
    {
        $entryList = KeepAChangelog\EntryList::create();

        self::assertSame([], $entryList->toArray());
    }

    public function testCreateReturnsEntryListWithEntries(): void
    {
        $faker = self::faker();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4));

        $entryList = KeepAChangelog\EntryList::create(...$values);

        self::assertSame($values, $entryList->toArray());
    }

    public function testSortedByPullRequestAscendingReturnsEntryListWithEntriesSortedByPullRequestAscending(): void
    {
        $faker = self::faker();

        $values = \array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4));

        $entryList = KeepAChangelog\EntryList::create(...$values);

        $mutated = $entryList->sortedByPullRequestAscending();

        self::assertNotSame($entryList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (KeepAChangelog\Entry $a, KeepAChangelog\Entry $b): int {
            return $a->pullRequest()->compare($b->pullRequest());
        });

        self::assertEquals(KeepAChangelog\EntryList::create(...$sorted), $mutated);
    }

    public function testIsEmptyReturnsTrueWhenEntryListIsEmpty(): void
    {
        $entryList = KeepAChangelog\EntryList::empty();

        self::assertTrue($entryList->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenEntryListIsNotEmpty(): void
    {
        $faker = self::faker();

        $entryList = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        self::assertFalse($entryList->isEmpty());
    }
}
