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

final class Repository
{
    private Owner $owner;
    private Name $name;
    private DefaultBranch $defaultBranch;
    private ?InitialCommit $initialCommit;

    private function __construct(
        Owner $owner,
        Name $name,
        DefaultBranch $defaultBranch,
        ?InitialCommit $initialCommit
    ) {
        $this->owner = $owner;
        $this->name = $name;
        $this->defaultBranch = $defaultBranch;
        $this->initialCommit = $initialCommit;
    }

    public static function create(
        Owner $owner,
        Name $name,
        DefaultBranch $defaultBranch,
        ?InitialCommit $initialCommit
    ): self {
        return new self(
            $owner,
            $name,
            $defaultBranch,
            $initialCommit,
        );
    }

    public function owner(): Owner
    {
        return $this->owner;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function defaultBranch(): DefaultBranch
    {
        return $this->defaultBranch;
    }

    public function initialCommit(): ?InitialCommit
    {
        return $this->initialCommit;
    }
}
