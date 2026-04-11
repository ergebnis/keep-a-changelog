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

use Ergebnis\KeepAChangelog\Author;
use Ergebnis\KeepAChangelog\InvalidAuthor;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Author
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidAuthor
 */
final class AuthorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidAuthor::class);

        Author::fromString($value);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $values = [
            'empty' => '',
            'leading-and-trailing-space' => ' localheinz ',
            'leading-hyphen' => '-localheinz',
            'leading-newline' => "\nlocalheinz",
            'leading-space' => ' localheinz',
            'leading-tab' => "\tlocalheinz",
            'length-too-long' => \str_repeat('a', 40),
            'trailing-hyphen' => 'localheinz-',
            'trailing-newline' => "localheinz\n",
            'trailing-space' => 'localheinz ',
            'trailing-tab' => "localheinz\t",
            'with-consecutive-hyphens' => 'local--heinz',
            'with-contains-punctuation' => 'localheinz!',
            'with-space' => 'local heinz',
            'with-underscore' => 'local_heinz',
        ];

        foreach ($values as $description => $value) {
            yield $description => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsAuthor(string $value): void
    {
        $author = Author::fromString($value);

        self::assertSame($value, $author->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'case-mixed-alphanumeric' => 'FooBar123',
            'case-mixed-with-hyphen' => 'Foo-Bar',
            'digits-only' => '123',
            'digits-with-hyphens' => '1-2-3',
            'handle-organization' => 'ergebnis',
            'handle-user' => 'localheinz',
            'length-maximum' => \str_repeat('a', 39),
            'length-minimum' => 'a',
        ];

        foreach ($values as $description => $value) {
            yield $description => [
                $value,
            ];
        }
    }
}
