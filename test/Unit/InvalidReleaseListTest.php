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

use Ergebnis\KeepAChangelog\InvalidReleaseList;
use Ergebnis\KeepAChangelog\Tag;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\InvalidReleaseList
 *
 * @uses \Ergebnis\KeepAChangelog\Tag
 */
final class InvalidReleaseListTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testWithDuplicateTagsReturnsException(): void
    {
        $faker = self::faker()->unique();

        $tags = \array_map(static function () use ($faker): Tag {
            return Tag::fromString($faker->semver());
        }, \range(0, 2));

        $exception = InvalidReleaseList::withDuplicateTags(...$tags);

        $expected = \sprintf(
            'Release list contains more than one release with tag(s) "%s".',
            \implode('", "', \array_map(static function (Tag $tag): string {
                return $tag->toString();
            }, $tags)),
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
