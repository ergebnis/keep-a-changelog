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

use Ergebnis\KeepAChangelog\InitialCommit;
use Ergebnis\KeepAChangelog\InvalidInitialCommit;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\InitialCommit
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidInitialCommit
 */
final class InitialCommitTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidInitialCommit::class);

        InitialCommit::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $faker = self::faker();

        $values = [
            'case-upper' => \mb_strtoupper($faker->sha1()),
            'length-too-long' => \str_repeat('a', 41),
            'length-too-short' => \str_repeat('a', 39),
            'md5' => $faker->md5(),
            'whitespace-leading' => \sprintf(
                ' %s',
                $faker->sha1(),
            ),
            'whitespace-leading-and-trailing' => \sprintf(
                ' %s ',
                $faker->sha1(),
            ),
            'whitespace-trailing' => \sprintf(
                ' %s',
                $faker->sha1(),
            ),
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
    public function testFromStringReturnsInitialCommit(string $value): void
    {
        $initialCommit = InitialCommit::fromString($value);

        self::assertSame($value, $initialCommit->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'all-digits' => '0000000000000000000000000000000000000001',
            'all-zeroes' => '0000000000000000000000000000000000000000',
            'mixed' => 'd3b2705a8b9c1f4e2d7a6b5c3e1f0a9b8c7d6e5f',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
