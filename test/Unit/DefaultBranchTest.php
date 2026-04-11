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

use Ergebnis\KeepAChangelog\DefaultBranch;
use Ergebnis\KeepAChangelog\InvalidDefaultBranch;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\DefaultBranch
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidDefaultBranch
 */
final class DefaultBranchTest extends Framework\TestCase
{
    public function testMainReturnsDefaultBranch(): void
    {
        $defaultBranch = DefaultBranch::main();

        self::assertSame('main', $defaultBranch->toString());
    }

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidDefaultBranch::class);

        DefaultBranch::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'contains-colon' => 'feature:one',
            'contains-question-mark' => 'feature?',
            'contains-space' => 'feature branch',
            'empty' => '',
            'leading-dot' => '.hidden',
            'leading-hyphen' => '-main',
            'length-too-long' => \str_repeat('a', 256),
            'multiline' => "main\ndev",
            'trailing-newline' => "main\n",
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
    public function testFromStringReturnsDefaultBranch(string $value): void
    {
        $defaultBranch = DefaultBranch::fromString($value);

        self::assertSame($value, $defaultBranch->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'branch-develop' => 'develop',
            'branch-main' => 'main',
            'branch-master' => 'master',
            'branch-trunk' => 'trunk',
            'length-max' => \str_repeat('a', 255),
            'length-min' => 'a',
            'nested' => 'feature/auth',
            'with-digits' => 'release-2024',
            'with-dot' => 'v1.0',
            'with-underscore' => 'release_branch',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
