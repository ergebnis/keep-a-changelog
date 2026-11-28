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
    use KeepAChangelog\Test\Util\Helper;

    public function testEmptyReturnsChanges(): void
    {
        $changes = KeepAChangelog\Changes::empty();

        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->added());
        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->changed());
        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->deprecated());
        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->removed());
        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->fixed());
        self::assertEquals(KeepAChangelog\EntryList::empty(), $changes->security());
    }

    public function testCreateReturnsChanges(): void
    {
        $faker = self::faker();

        $added = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $changed = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $deprecated = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $removed = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $fixed = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $security = KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
            return KeepAChangelog\Entry::create(
                KeepAChangelog\Description::fromString($faker->sentence()),
                KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                KeepAChangelog\Author::fromString($faker->slug(2)),
                KeepAChangelog\Notes::fromString($faker->realText()),
            );
        }, \range(0, 4)));

        $changes = KeepAChangelog\Changes::create(
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

    public function testIsEmptyReturnsFalseWhenChangesHasAdded(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenChangesHasChanged(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenChangesHasDeprecated(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenChangesHasFixed(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenChangesHasRemoved(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::empty(),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenChangesHasSecurity(): void
    {
        $faker = self::faker();

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::empty(),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
        );

        self::assertFalse($changes->isEmpty());
    }

    public function testIsEmptyReturnsTrueWhenAllEntryListsAreEmpty(): void
    {
        $changes = KeepAChangelog\Changes::empty();

        self::assertTrue($changes->isEmpty());
    }
}
