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

final class Entry
{
    private Description $description;
    private PullRequest $pullRequest;
    private Author $author;
    private ?Notes $notes;

    private function __construct(
        Description $description,
        PullRequest $pullRequest,
        Author $author,
        ?Notes $notes
    ) {
        $this->description = $description;
        $this->pullRequest = $pullRequest;
        $this->author = $author;
        $this->notes = $notes;
    }

    public static function create(
        Description $description,
        PullRequest $pullRequest,
        Author $author,
        ?Notes $notes
    ): self {
        return new self(
            $description,
            $pullRequest,
            $author,
            $notes,
        );
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function pullRequest(): PullRequest
    {
        return $this->pullRequest;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function notes(): ?Notes
    {
        return $this->notes;
    }
}
