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

use Ergebnis\KeepAChangelog\Tag;
use Ergebnis\KeepAChangelog\UnknownRelease;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\UnknownRelease
 *
 * @uses \Ergebnis\KeepAChangelog\Tag
 */
final class UnknownReleaseTest extends Framework\TestCase
{
    public function testNamedReturnsException(): void
    {
        $tag = Tag::fromString('1.2.3');

        $exception = UnknownRelease::named($tag);

        $expected = \sprintf(
            'Tag "%s" does not refer to a known release.',
            $tag->toString(),
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
