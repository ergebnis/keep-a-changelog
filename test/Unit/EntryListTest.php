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

use Ergebnis\KeepAChangelog\Author;
use Ergebnis\KeepAChangelog\Description;
use Ergebnis\KeepAChangelog\Entry;
use Ergebnis\KeepAChangelog\EntryList;
use Ergebnis\KeepAChangelog\Notes;
use Ergebnis\KeepAChangelog\PullRequest;
use Ergebnis\KeepAChangelog\Test;
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
    use Test\Util\Helper;

    public function testEmptyReturnsEntryList(): void
    {
        $entryList = EntryList::empty();

        self::assertSame([], $entryList->toArray());
    }

    public function testCreateReturnsEntryList(): void
    {
        $entryList = EntryList::create();

        self::assertSame([], $entryList->toArray());
    }

    public function testCreateReturnsEntryListWithEntries(): void
    {
        $faker = self::faker();

        $values = \array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString($faker->slug(2)),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4));

        $entryList = EntryList::create(...$values);

        self::assertSame($values, $entryList->toArray());
    }

    public function testSortByPullRequestAscendingReturnsEntryListWithEntriesSortedByPullRequestAscending(): void
    {
        $faker = self::faker();

        $values = \array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString($faker->slug(2)),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4));

        $entryList = EntryList::create(...$values);

        $mutated = $entryList->sortByPullRequestAscending();

        self::assertNotSame($entryList, $mutated);

        $sorted = $values;

        \usort($sorted, static function (Entry $a, Entry $b): int {
            return $a->pullRequest()->compare($b->pullRequest());
        });

        self::assertEquals(EntryList::create(...$sorted), $mutated);
    }
}
