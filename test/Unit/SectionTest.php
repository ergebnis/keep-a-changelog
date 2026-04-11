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

use Ergebnis\KeepAChangelog\Section;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\KeepAChangelog\UnknownSection;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Section
 *
 * @uses \Ergebnis\KeepAChangelog\UnknownSection
 */
final class SectionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testAddedReturnsSectionWithValueAdded(): void
    {
        $section = Section::added();

        self::assertSame('added', $section->toString());
    }

    public function testChangedReturnsSectionWithValueChanged(): void
    {
        $section = Section::changed();

        self::assertSame('changed', $section->toString());
    }

    public function testDeprecatedReturnsSectionWithValueDeprecated(): void
    {
        $section = Section::deprecated();

        self::assertSame('deprecated', $section->toString());
    }

    public function testRemovedReturnsSectionWithValueRemoved(): void
    {
        $section = Section::removed();

        self::assertSame('removed', $section->toString());
    }

    public function testFixedReturnsSectionWithValueFixed(): void
    {
        $section = Section::fixed();

        self::assertSame('fixed', $section->toString());
    }

    public function testSecurityReturnsSectionWithValueSecurity(): void
    {
        $section = Section::security();

        self::assertSame('security', $section->toString());
    }

    public function testFromStringRejectsUnknownValue(): void
    {
        $value = self::faker()->word();

        $this->expectException(UnknownSection::class);

        Section::fromString($value);
    }

    /**
     * @dataProvider provideValue
     */
    public function testFromStringReturnsSection(string $value): void
    {
        $section = Section::fromString($value);

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
        $one = Section::added();
        $two = Section::fixed();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsTheSame(): void
    {
        $one = Section::added();
        $two = Section::added();

        self::assertTrue($one->equals($two));
    }
}
