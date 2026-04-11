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
use Ergebnis\KeepAChangelog\Changes;
use Ergebnis\KeepAChangelog\Description;
use Ergebnis\KeepAChangelog\Entry;
use Ergebnis\KeepAChangelog\EntryList;
use Ergebnis\KeepAChangelog\Notes;
use Ergebnis\KeepAChangelog\PullRequest;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Changes
 *
 * @uses \Ergebnis\KeepAChangelog\Author
 * @uses \Ergebnis\KeepAChangelog\Description
 * @uses \Ergebnis\KeepAChangelog\Entry
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\Notes
 * @uses \Ergebnis\KeepAChangelog\PullRequest
 */
final class ChangesTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testEmptyReturnsChanges(): void
    {
        $changes = Changes::empty();

        self::assertEquals(EntryList::empty(), $changes->added());
        self::assertEquals(EntryList::empty(), $changes->changed());
        self::assertEquals(EntryList::empty(), $changes->deprecated());
        self::assertEquals(EntryList::empty(), $changes->removed());
        self::assertEquals(EntryList::empty(), $changes->fixed());
        self::assertEquals(EntryList::empty(), $changes->security());
    }

    public function testCreateReturnsChanges(): void
    {
        $faker = self::faker();

        $added = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $changed = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $deprecated = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $removed = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $fixed = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $security = EntryList::create(...\array_map(static function () use ($faker): Entry {
            return Entry::create(
                Description::fromString($faker->sentence()),
                PullRequest::fromInt($faker->numberBetween(1)),
                Author::fromString('localheinz'),
                Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $changes = Changes::create(
            $added,
            $changed,
            $deprecated,
            $removed,
            $fixed,
            $security,
        );

        self::assertSame($added, $changes->added());
        self::assertSame($changed, $changes->changed());
        self::assertSame($deprecated, $changes->deprecated());
        self::assertSame($removed, $changes->removed());
        self::assertSame($fixed, $changes->fixed());
        self::assertSame($security, $changes->security());
    }
}
