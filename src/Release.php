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

final class Release
{
    private Version\Version $version;
    private Changes $changes;

    private function __construct(
        Version\Version $version,
        Changes $changes
    ) {
        $this->version = $version;
        $this->changes = $changes;
    }

    public static function create(
        Version\Version $version,
        Changes $changes
    ): self {
        return new self(
            $version,
            $changes,
        );
    }

    public function version(): Version\Version
    {
        return $this->version;
    }

    public function changes(): Changes
    {
        return $this->changes;
    }
}
