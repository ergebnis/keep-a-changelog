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

final class EntryList
{
    /**
     * @var list<Entry>
     */
    private array $values;

    /**
     * @param list<Entry> $values
     */
    private function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function create(Entry ...$values): self
    {
        return new self($values);
    }

    /**
     * @return list<Entry>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    public function sortedByPullRequestAscending(): self
    {
        $sorted = $this->values;

        \usort($sorted, static function (Entry $a, Entry $b): int {
            return $a->pullRequest()->compare($b->pullRequest());
        });

        return new self($sorted);
    }

    public function isEmpty(): bool
    {
        return [] === $this->values;
    }
}
