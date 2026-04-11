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

use Ergebnis\KeepAChangelog\InvalidPullRequest;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\InvalidPullRequest
 */
final class InvalidPullRequestTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromReturnsException(): void
    {
        $value = self::faker()->numberBetween(-1000, 0);

        $exception = InvalidPullRequest::from($value);

        $expected = \sprintf(
            'Value %d is not a valid pull request number.',
            $value,
        );

        self::assertSame($expected, $exception->getMessage());
    }
}
