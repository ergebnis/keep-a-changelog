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

use Ergebnis\KeepAChangelog\Description;
use Ergebnis\KeepAChangelog\InvalidDescription;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Description
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidDescription
 */
final class DescriptionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidDescription::class);

        Description::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'blank-spaces' => '   ',
            'blank-tabs' => "\t\t",
            'contains-cr' => "foo\rbar",
            'contains-crlf' => "foo\r\nbar",
            'contains-lf' => "foo\nbar",
            'empty' => '',
            'leading-newline' => "\nfoo",
            'leading-space' => ' foo',
            'leading-tab' => "\tfoo",
            'trailing-newline' => "foo\n",
            'trailing-space' => 'foo ',
            'trailing-tab' => "foo\t",
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsDescription(string $value): void
    {
        $description = Description::fromString($value);

        self::assertSame($value, $description->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'plain-words' => 'Added Foo',
            'single-character' => 'a',
            'with-backticks' => 'Added `Foo`',
            'with-punctuation' => 'Fixed: issue #42',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
