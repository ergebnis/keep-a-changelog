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

use Ergebnis\KeepAChangelog\MarkdownBuffer;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\MarkdownBuffer
 */
final class MarkdownBufferTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFlushReturnsEmptyStringWhenNoLinesHaveBeenBuffered(): void
    {
        $markdownBuffer = new MarkdownBuffer();

        self::assertSame('', $markdownBuffer->flush());
    }

    public function testFlushReturnsStringWhenLinesHaveBeenBuffered(): void
    {
        /** @var list<string> $lines */
        $lines = self::faker()->sentences(3);

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append(...$lines);

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     */
    public function testFlushRightTrimsLines(string $value): void
    {
        /** @var list<string> $lines */
        $lines = self::faker()->sentences(3);

        $untrimmed = \array_map(static function (string $line) use ($value): string {
            return \sprintf(
                '%s%s',
                $line,
                $value,
            );
        }, $lines);

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append(...$untrimmed);

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    public function testFlushIgnoresLeadingEmptyLines(): void
    {
        /** @var list<string> $lines */
        $lines = self::faker()->sentences(3);

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append('');
        $markdownBuffer->append(...$lines);

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFlushIgnoresBlankOrEmptyLinesOnly(string $value): void
    {
        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append($value);
        $markdownBuffer->append($value);
        $markdownBuffer->append($value);

        self::assertSame('', $markdownBuffer->flush());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFlushIgnoresTrailingEmptyLines(string $value): void
    {
        /** @var list<string> $lines */
        $lines = self::faker()->sentences(3);

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append(...$lines);
        $markdownBuffer->append($value);

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    public function testFlushAcceptsSingleEmptyLineBetweenNonEmptyLines(): void
    {
        $faker = self::faker();

        /** @var list<string> $lines */
        $lines = [
            $faker->sentence(),
            '',
            $faker->sentence(),
            '',
            $faker->sentence(),
        ];

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append(...$lines);

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFlushCollapsesConsecutiveBlankOrEmptyLines(string $value): void
    {
        $faker = self::faker();

        $one = $faker->sentence();
        $two = $faker->sentence();
        $three = $faker->sentence();
        $four = $faker->sentence();
        $five = $faker->sentence();

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append($one);

        $markdownBuffer->append($value);
        $markdownBuffer->append($value);

        $markdownBuffer->append($two);
        $markdownBuffer->append($three);

        $markdownBuffer->append($value);
        $markdownBuffer->append($value);
        $markdownBuffer->append($value);

        $markdownBuffer->append($four);
        $markdownBuffer->append($five);

        $lines = [
            $one,
            '',
            $two,
            $three,
            '',
            $four,
            $five,
        ];

        $expected = \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $lines));

        self::assertSame($expected, $markdownBuffer->flush());
    }

    public function testFlushClearsBuffer(): void
    {
        /** @var list<string> $lines */
        $lines = self::faker()->sentences(3);

        $markdownBuffer = new MarkdownBuffer();

        $markdownBuffer->append(...$lines);

        $markdownBuffer->flush();

        self::assertSame('', $markdownBuffer->flush());
    }
}
