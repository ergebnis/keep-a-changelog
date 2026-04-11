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

use Ergebnis\KeepAChangelog\InvalidNotes;
use Ergebnis\KeepAChangelog\Notes;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Notes
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidNotes
 */
final class NotesTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     * @dataProvider \Ergebnis\DataProvider\StringProvider::untrimmed
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(InvalidNotes::class);

        Notes::fromString($value);
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsNotes(string $value): void
    {
        $notes = Notes::fromString($value);

        self::assertSame($value, $notes->toString());
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $values = [
            'multi-line' => "Line one\nLine two",
            'multi-line-with-blank-line' => "Line one\n\nLine three",
            'multi-line-with-crlf' => "Line one\r\nLine two",
            'single-character' => 'a',
            'single-line' => 'Notes about this change.',
            'with-markdown-link' => 'See [the spec](https://example.com).',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
