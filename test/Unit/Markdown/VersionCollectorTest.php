<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/keep-a-changelog
 */

namespace Ergebnis\KeepAChangelog\Test\Unit\Markdown;

use Ergebnis\KeepAChangelog\Markdown;
use Ergebnis\KeepAChangelog\Test;
use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\KeepAChangelog\Markdown\VersionCollector
 */
final class VersionCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCollectReturnsEmptyArrayWhenContentIsEmptyString(): void
    {
        $content = '';

        $versionCollector = new Markdown\VersionCollector();

        $collected = $versionCollector->collect($content);

        self::assertSame([], $collected);
    }

    public function testCollectReturnsEmptyArrayWhenContentDoesNotContainAnyVersions(): void
    {
        $content = self::faker()->realText();

        $versionCollector = new Markdown\VersionCollector();

        $collected = $versionCollector->collect($content);

        self::assertSame([], $collected);
    }

    public function testCollectReturnsArrayWithVersionsWhenContentContainsInvalidVersions(): void
    {
        $content = \file_get_contents(__DIR__ . '/../../Fixture/broken/CHANGELOG.md');

        $versionCollector = new Markdown\VersionCollector();

        $collected = $versionCollector->collect($content);

        $expected = [
            Version\Version::fromString('1.0.0'),
            Version\Version::fromString('1.1.0'),
        ];

        self::assertEquals($expected, $collected);
    }

    public function testCollectReturnsArrayWithVersionsWhenContentContainsVersions(): void
    {
        $content = \file_get_contents(__DIR__ . '/../../Fixture/ergebnis/version/CHANGELOG.md');

        $versionCollector = new Markdown\VersionCollector();

        $collected = $versionCollector->collect($content);

        $expected = [
            Version\Version::fromString('1.0.0'),
            Version\Version::fromString('1.1.0'),
        ];

        self::assertEquals($expected, $collected);
    }
}
