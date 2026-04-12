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

use Ergebnis\KeepAChangelog\InvalidTag;
use Ergebnis\KeepAChangelog\Tag;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Tag
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidTag
 */
final class TagTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidTag::class);

        Tag::fromString($value);
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

        $tag = Tag::fromString($value);

        self::assertSame($value, $tag->toString());
    }

    public function testCompareReturnsMinusOneWhenTagIsLess(): void
    {
        $one = Tag::fromString('1.0.0');
        $two = Tag::fromString('2.0.0');

        self::assertSame(-1, $one->compare($two));
    }

    public function testCompareReturnsZeroWhenTagsAreEqual(): void
    {
        $one = Tag::fromString('1.0.0');
        $two = Tag::fromString('1.0.0');

        self::assertSame(0, $one->compare($two));
    }

    public function testCompareReturnsPlusOneWhenTagIsGreater(): void
    {
        $one = Tag::fromString('2.0.0');
        $two = Tag::fromString('1.0.0');

        self::assertSame(1, $one->compare($two));
    }
}
