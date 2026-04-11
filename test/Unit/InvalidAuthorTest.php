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

use Ergebnis\KeepAChangelog\InvalidAuthor;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\InvalidAuthor
 */
final class InvalidAuthorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNamedReturnsException(): void
    {
        $value = self::faker()->word();

        $exception = InvalidAuthor::named($value);

        $expected = \sprintf(
            'Value "%s" is not a valid author.',
            $value,
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
