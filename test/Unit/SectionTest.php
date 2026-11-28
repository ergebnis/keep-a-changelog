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
 * @covers \Ergebnis\KeepAChangelog\Section
 *
 * @uses \Ergebnis\KeepAChangelog\UnknownSection
 */
final class SectionTest extends Framework\TestCase
{
    use KeepAChangelog\Test\Util\Helper;

    public function testAddedReturnsSectionWithValueAdded(): void
    {
        $section = KeepAChangelog\Section::added();

        self::assertSame('added', $section->toString());
    }

    public function testChangedReturnsSectionWithValueChanged(): void
    {
        $section = KeepAChangelog\Section::changed();

        self::assertSame('changed', $section->toString());
    }

    public function testDeprecatedReturnsSectionWithValueDeprecated(): void
    {
        $section = KeepAChangelog\Section::deprecated();

        self::assertSame('deprecated', $section->toString());
    }

    public function testRemovedReturnsSectionWithValueRemoved(): void
    {
        $section = KeepAChangelog\Section::removed();

        self::assertSame('removed', $section->toString());
    }

    public function testFixedReturnsSectionWithValueFixed(): void
    {
        $section = KeepAChangelog\Section::fixed();

        self::assertSame('fixed', $section->toString());
    }

    public function testSecurityReturnsSectionWithValueSecurity(): void
    {
        $section = KeepAChangelog\Section::security();

        self::assertSame('security', $section->toString());
    }

    public function testFromStringRejectsUnknownValue(): void
    {
        $value = self::faker()->word();

        $this->expectException(KeepAChangelog\UnknownSection::class);

        KeepAChangelog\Section::fromString($value);
    }

    /**
     * @dataProvider provideValue
     */
    public function testFromStringReturnsSection(string $value): void
    {
        $section = KeepAChangelog\Section::fromString($value);

        self::assertSame($value, $section->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValue(): iterable
    {
        $values = [
            'added',
            'changed',
            'deprecated',
            'removed',
            'fixed',
            'security',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $one = KeepAChangelog\Section::added();
        $two = KeepAChangelog\Section::fixed();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsTheSame(): void
    {
        $one = KeepAChangelog\Section::added();
        $two = KeepAChangelog\Section::added();

        self::assertTrue($one->equals($two));
    }
}
