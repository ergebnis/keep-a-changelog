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
 * @covers \Ergebnis\KeepAChangelog\Tag
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidTag
 */
final class TagTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(KeepAChangelog\InvalidTag::class);

        KeepAChangelog\Tag::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'empty' => '',
            'leading-v' => 'v1.0.0',
            'missing-patch' => '1.0',
            'not-semver' => 'not-a-semver',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testFromStringReturnsTag(): void
    {
        $value = self::faker()->semver();

        $tag = KeepAChangelog\Tag::fromString($value);

        self::assertSame($value, $tag->toString());
    }

    public function testCompareReturnsMinusOneWhenTagIsLess(): void
    {
        $one = KeepAChangelog\Tag::fromString('1.0.0');
        $two = KeepAChangelog\Tag::fromString('2.0.0');

        self::assertSame(-1, $one->compare($two));
    }

    public function testCompareReturnsZeroWhenTagsAreEqual(): void
    {
        $one = KeepAChangelog\Tag::fromString('1.0.0');
        $two = KeepAChangelog\Tag::fromString('1.0.0');

        self::assertSame(0, $one->compare($two));
    }

    public function testCompareReturnsPlusOneWhenTagIsGreater(): void
    {
        $one = KeepAChangelog\Tag::fromString('2.0.0');
        $two = KeepAChangelog\Tag::fromString('1.0.0');

        self::assertSame(1, $one->compare($two));
    }

    public function testEqualsReturnsFalseWhenTagsAreNotEqual(): void
    {
        $one = KeepAChangelog\Tag::fromString('1.0.0');
        $two = KeepAChangelog\Tag::fromString('2.0.0');

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenTagsAreEqual(): void
    {
        $one = KeepAChangelog\Tag::fromString('1.0.0');
        $two = KeepAChangelog\Tag::fromString('1.0.0');

        self::assertTrue($one->equals($two));
    }
}
