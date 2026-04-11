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

use Ergebnis\KeepAChangelog\DefaultBranch;
use Ergebnis\KeepAChangelog\InitialCommit;
use Ergebnis\KeepAChangelog\Name;
use Ergebnis\KeepAChangelog\Owner;
use Ergebnis\KeepAChangelog\Repository;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Repository
 *
 * @uses \Ergebnis\KeepAChangelog\DefaultBranch
 * @uses \Ergebnis\KeepAChangelog\InitialCommit
 * @uses \Ergebnis\KeepAChangelog\Name
 * @uses \Ergebnis\KeepAChangelog\Owner
 */
final class RepositoryTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsRepositoryWhenNullableValuesAreNotNull(): void
    {
        $faker = self::faker();

        $owner = Owner::fromString($faker->slug(2));
        $name = Name::fromString($faker->slug(2));
        $defaultBranch = DefaultBranch::fromString($faker->slug(2));
        $initialCommit = InitialCommit::fromString($faker->sha1());

        $repository = Repository::create(
            $owner,
            $name,
            $defaultBranch,
            $initialCommit,
        );

        self::assertSame($owner, $repository->owner());
        self::assertSame($name, $repository->name());
        self::assertSame($defaultBranch, $repository->defaultBranch());
        self::assertSame($initialCommit, $repository->initialCommit());
    }

    public function testCreateReturnsRepositoryWhenNullableValuesAreNull(): void
    {
        $faker = self::faker();

        $owner = Owner::fromString($faker->slug(2));
        $name = Name::fromString($faker->slug(2));
        $defaultBranch = DefaultBranch::fromString($faker->slug(2));

        $repository = Repository::create(
            $owner,
            $name,
            $defaultBranch,
            null,
        );

        self::assertSame($owner, $repository->owner());
        self::assertSame($name, $repository->name());
        self::assertSame($defaultBranch, $repository->defaultBranch());
        self::assertNull($repository->initialCommit());
    }
}
