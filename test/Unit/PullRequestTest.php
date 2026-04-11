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
use Ergebnis\KeepAChangelog\PullRequest;
use Ergebnis\KeepAChangelog\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\PullRequest
 *
 * @uses \Ergebnis\KeepAChangelog\InvalidPullRequest
 */
final class PullRequestTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanOne
     */
    public function testFromIntRejectsInvalidValue(int $value): void
    {
        $this->expectException(InvalidPullRequest::class);

        PullRequest::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromIntReturnsPullRequest(int $value): void
    {
        $pullRequest = PullRequest::fromInt($value);

        self::assertSame($value, $pullRequest->toInt());
    }

    public function testCompareReturnsMinusOneWhenValueIsLessThanOther(): void
    {
        $value = self::faker()->numberBetween(1);

        $one = PullRequest::fromInt($value);
        $two = PullRequest::fromInt($value + 1);

        self::assertSame(-1, $one->compare($two));
    }

    public function testCompareReturnsZeroWhenValuesAreEqual(): void
    {
        $value = self::faker()->numberBetween(1);

        $one = PullRequest::fromInt($value);
        $two = PullRequest::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    public function testCompareReturnsPlusOneWhenValueIsGreaterThanOther(): void
    {
        $value = self::faker()->numberBetween(1);

        $one = PullRequest::fromInt($value + 1);
        $two = PullRequest::fromInt($value);

        self::assertSame(1, $one->compare($two));
    }
}
