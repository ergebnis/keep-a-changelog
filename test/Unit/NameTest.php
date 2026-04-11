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

use Ergebnis\KeepAChangelog\InvalidName;
use Ergebnis\KeepAChangelog\Name;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Name
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidName
 */
final class NameTest extends Framework\TestCase
{
    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidName::class);

        Name::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'contains-punctuation' => 'keep-a-changelog!',
            'contains-slash' => 'ergebnis/keep-a-changelog',
            'contains-space' => 'keep a changelog',
            'dot' => '.',
            'dot-dot' => '..',
            'empty' => '',
            'length-too-long' => \str_repeat('a', 101),
            'multiline' => "keep-a\nchangelog",
            'trailing-newline' => "keep-a-changelog\n",
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
    public function testFromStringReturnsName(string $value): void
    {
        $name = Name::fromString($value);

        self::assertSame($value, $name->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'dotted' => '.github',
            'length-max' => \str_repeat('a', 100),
            'length-min' => 'a',
            'name-simple' => 'keep-a-changelog',
            'name-with-digits' => 'php7',
            'name-with-dot' => 'package.json',
            'name-with-underscore' => 'keep_a_changelog',
            'single-digit' => '1',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
