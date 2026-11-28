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
 * @covers \Ergebnis\KeepAChangelog\Changelog
 *
 * @uses \Ergebnis\KeepAChangelog\Changes
 * @uses \Ergebnis\KeepAChangelog\DefaultBranch
 * @uses \Ergebnis\KeepAChangelog\EntryList
 * @uses \Ergebnis\KeepAChangelog\InitialCommit
 * @uses \Ergebnis\KeepAChangelog\Name
 * @uses \Ergebnis\KeepAChangelog\Owner
 * @uses \Ergebnis\KeepAChangelog\Release
 * @uses \Ergebnis\KeepAChangelog\ReleaseList
 * @uses \Ergebnis\KeepAChangelog\Repository
 * @uses \Ergebnis\KeepAChangelog\Tag
 * @uses \Ergebnis\KeepAChangelog\Unreleased
 */
final class ChangelogTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    public function testCreateReturnsChangelog(): void
    {
        $faker = self::faker();

        $repository = KeepAChangelog\Repository::create(
            KeepAChangelog\Owner::fromString($faker->slug(2)),
            KeepAChangelog\Name::fromString($faker->slug(2)),
            KeepAChangelog\DefaultBranch::fromString($faker->slug(2)),
            KeepAChangelog\InitialCommit::fromString($faker->sha1()),
        );

        $unreleased = KeepAChangelog\Unreleased::empty();

        $uniqueFaker = self::faker()->unique();

        $releases = KeepAChangelog\ReleaseList::create(...\array_map(static function () use ($uniqueFaker): KeepAChangelog\Release {
            return KeepAChangelog\Release::create(
                KeepAChangelog\Tag::fromString($uniqueFaker->semver()),
                KeepAChangelog\Changes::empty(),
            );
        }, \range(0, 2)));

        $changelog = KeepAChangelog\Changelog::create(
            $repository,
            $unreleased,
            $releases,
        );

        self::assertSame($repository, $changelog->repository());
        self::assertSame($unreleased, $changelog->unreleased());
        self::assertSame($releases, $changelog->releases());
    }
}
