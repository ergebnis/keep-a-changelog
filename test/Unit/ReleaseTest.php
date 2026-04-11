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
use Ergebnis\KeepAChangelog\Release;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Release
 *
 * @uses \Ergebnis\KeepAChangelog\Author
 * @uses \Ergebnis\KeepAChangelog\Changes
 * @uses \Ergebnis\KeepAChangelog\Description
 * @uses \Ergebnis\KeepAChangelog\Entry
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\Notes
 * @uses \Ergebnis\KeepAChangelog\PullRequest
 */
final class ReleaseTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsRelease(): void
    {
        $faker = self::faker();

        $version = Version\Version::fromString($faker->semver());

        $changes = Changes::create(
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            EntryList::create(...\array_map(static function () use ($faker): Entry {
                return Entry::create(
                    Description::fromString($faker->sentence()),
                    PullRequest::fromInt($faker->numberBetween(1)),
                    Author::fromString($faker->slug(2)),
                    Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
        );

        $release = Release::create(
            $version,
            $changes,
        );

        self::assertSame($version, $release->version());
        self::assertSame($changes, $release->changes());
    }
}
