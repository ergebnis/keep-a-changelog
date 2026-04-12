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

use Ergebnis\KeepAChangelog\Changelog;
use Ergebnis\KeepAChangelog\Changes;
use Ergebnis\KeepAChangelog\DefaultBranch;
use Ergebnis\KeepAChangelog\InitialCommit;
use Ergebnis\KeepAChangelog\Name;
use Ergebnis\KeepAChangelog\Owner;
use Ergebnis\KeepAChangelog\Release;
use Ergebnis\KeepAChangelog\ReleaseList;
use Ergebnis\KeepAChangelog\Repository;
use Ergebnis\KeepAChangelog\Tag;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\KeepAChangelog\Unreleased;
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
    use Test\Util\Helper;

    public function testCreateReturnsChangelog(): void
    {
        $faker = self::faker();

        $repository = Repository::create(
            Owner::fromString($faker->slug(2)),
            Name::fromString($faker->slug(2)),
            DefaultBranch::fromString($faker->slug(2)),
            InitialCommit::fromString($faker->sha1()),
        );

        $unreleased = Unreleased::empty();

        $uniqueFaker = self::faker()->unique();

        $releases = ReleaseList::create(...\array_map(static function () use ($uniqueFaker): Release {
            return Release::create(
                Tag::fromString($uniqueFaker->semver()),
                Changes::empty(),
            );
        }, \range(0, 2)));

        $changelog = Changelog::create(
            $repository,
            $unreleased,
            $releases,
        );

        self::assertSame($repository, $changelog->repository());
        self::assertSame($unreleased, $changelog->unreleased());
        self::assertSame($releases, $changelog->releases());
    }
}
