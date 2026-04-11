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
use Ergebnis\KeepAChangelog\Notes;
use Ergebnis\KeepAChangelog\PullRequest;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Entry
 *
 * @uses \Ergebnis\KeepAChangelog\Author
 * @uses \Ergebnis\KeepAChangelog\Description
 * @uses \Ergebnis\KeepAChangelog\Notes
 * @uses \Ergebnis\KeepAChangelog\PullRequest
 */
final class EntryTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsEntryWhenNullableValuesAreNotNull(): void
    {
        $faker = self::faker();

        $description = Description::fromString($faker->sentence());
        $pullRequest = PullRequest::fromInt($faker->numberBetween(1));
        $author = Author::fromString('localheinz');
        $notes = Notes::fromString($faker->realText());

        $entry = Entry::create(
            $description,
            $pullRequest,
            $author,
            $notes,
        );

        self::assertSame($description, $entry->description());
        self::assertSame($pullRequest, $entry->pullRequest());
        self::assertSame($author, $entry->author());
        self::assertSame($notes, $entry->notes());
    }

    public function testCreateReturnsEntryWhenNullableValuesAreNull(): void
    {
        $faker = self::faker();

        $description = Description::fromString($faker->sentence());
        $pullRequest = PullRequest::fromInt($faker->numberBetween(1));
        $author = Author::fromString('localheinz');

        $entry = Entry::create(
            $description,
            $pullRequest,
            $author,
            null,
        );

        self::assertSame($description, $entry->description());
        self::assertSame($pullRequest, $entry->pullRequest());
        self::assertSame($author, $entry->author());
        self::assertNull($entry->notes());
    }
}
