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
 * @covers \Ergebnis\KeepAChangelog\Release
 *
 * @uses \Ergebnis\KeepAChangelog\Author
 * @uses \Ergebnis\KeepAChangelog\Changes
 * @uses \Ergebnis\KeepAChangelog\Description
 * @uses \Ergebnis\KeepAChangelog\Entry
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\Notes
 * @uses \Ergebnis\KeepAChangelog\PullRequest
 * @uses \Ergebnis\KeepAChangelog\Tag
 */
final class ReleaseTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    public function testCreateReturnsRelease(): void
    {
        $faker = self::faker();

        $tag = KeepAChangelog\Tag::fromString($faker->semver());

        $changes = KeepAChangelog\Changes::create(
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
            KeepAChangelog\EntryList::create(...\array_map(static function () use ($faker): KeepAChangelog\Entry {
                return KeepAChangelog\Entry::create(
                    KeepAChangelog\Description::fromString($faker->sentence()),
                    KeepAChangelog\PullRequest::fromInt($faker->numberBetween(1)),
                    KeepAChangelog\Author::fromString($faker->slug(2)),
                    KeepAChangelog\Notes::fromString($faker->realText()),
                );
            }, \range(0, 4))),
        );

        $release = KeepAChangelog\Release::create(
            $tag,
            $changes,
        );

        self::assertSame($tag, $release->tag());
        self::assertSame($changes, $release->changes());
    }
}
