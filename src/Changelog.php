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

final class Changelog
{
    private Repository $repository;
    private Unreleased $unreleased;
    private ReleaseList $releases;

    private function __construct(
        Repository $repository,
        Unreleased $unreleased,
        ReleaseList $releases
    ) {
        $this->repository = $repository;
        $this->unreleased = $unreleased;
        $this->releases = $releases;
    }

    public static function create(
        Repository $repository,
        Unreleased $unreleased,
        ReleaseList $releases
    ): self {
        return new self(
            $repository,
            $unreleased,
            $releases,
        );
    }

    public function repository(): Repository
    {
        return $this->repository;
    }

    public function unreleased(): Unreleased
    {
        return $this->unreleased;
    }

    public function releases(): ReleaseList
    {
        return $this->releases;
    }
}
