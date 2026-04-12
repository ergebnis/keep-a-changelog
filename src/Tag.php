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

namespace Ergebnis\KeepAChangelog;

use Ergebnis\Version;

final class Tag implements Reference
{
    private Version\Version $version;

    private function __construct(Version\Version $version)
    {
        $this->version = $version;
    }

    /**
     * @throws InvalidTag
     */
    public static function fromString(string $value): self
    {
        try {
            $version = Version\Version::fromString($value);
        } catch (Version\Exception\InvalidVersion $exception) {
            throw InvalidTag::named($value);
        }

        return new self($version);
    }

    public function toString(): string
    {
        return $this->version->toString();
    }

    public function compare(self $other): int
    {
        return $this->version->compare($other->version);
    }
}
