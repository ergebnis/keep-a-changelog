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

use Ergebnis\KeepAChangelog\InvalidOwner;
use Ergebnis\KeepAChangelog\Owner;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Owner
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidOwner
 */
final class OwnerTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidOwner::class);

        Owner::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'consecutive-hyphens' => 'local--heinz',
            'contains-punctuation' => 'localheinz!',
            'empty' => '',
            'leading-hyphen' => '-localheinz',
            'leading-space' => ' localheinz',
            'length-too-long' => \str_repeat('a', 40),
            'multiline' => "local\nheinz",
            'only-hyphen' => '-',
            'trailing-hyphen' => 'localheinz-',
            'trailing-newline' => "localheinz\n",
            'trailing-space' => 'localheinz ',
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
    public function testFromStringReturnsOwner(string $value): void
    {
        $owner = Owner::fromString($value);

        self::assertSame($value, $owner->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'handle-org' => 'ergebnis',
            'handle-user' => 'localheinz',
            'handle-with-digits' => 'user123',
            'handle-with-hyphen' => 'ergebnis-gmbh',
            'length-max' => \str_repeat('a', 39),
            'length-min' => 'a',
            'mixed-case' => 'LocalHeinz',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
