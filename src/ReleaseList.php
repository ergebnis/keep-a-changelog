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

final class ReleaseList
{
    /**
     * @var list<Release>
     */
    private array $values;

    /**
     * @param list<Release> $values
     */
    private function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @throws InvalidReleaseList
     */
    public static function create(Release ...$values): self
    {
        $duplicateTags = [];

        $tags = [];

        foreach ($values as $value) {
            $tag = $value->tag();

            if (\in_array($tag->toString(), $tags, true)) {
                $duplicateTags[] = $value->tag();
            }

            $tags[] = $tag->toString();
        }

        if ([] !== $duplicateTags) {
            throw InvalidReleaseList::withDuplicateTags(...$duplicateTags);
        }

        return new self($values);
    }

    /**
     * @return list<Release>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    public function releaseFor(Tag $tag): ?Release
    {
        foreach ($this->values as $value) {
            if ($value->tag()->equals($tag)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @throws UnknownRelease
     */
    public function previousReleaseFor(Tag $tag): ?Release
    {
        $previousRelease = null;

        foreach ($this->sortedByTagAscending()->toArray() as $value) {
            if (!$value->tag()->equals($tag)) {
                $previousRelease = $value;

                continue;
            }

            return $previousRelease;
        }

        throw UnknownRelease::named($tag);
    }

    public function sortedByTagAscending(): self
    {
        $sorted = $this->values;

        \usort($sorted, static function (Release $a, Release $b): int {
            return $a->tag()->compare($b->tag());
        });

        return new self($sorted);
    }

    public function first(): ?Release
    {
        if ([] === $this->values) {
            return null;
        }

        return $this->values[0];
    }

    public function last(): ?Release
    {
        if ([] === $this->values) {
            return null;
        }

        return $this->values[\count($this->values) - 1];
    }

    public function sortedByTagDescending(): self
    {
        $sorted = $this->values;

        \usort($sorted, static function (Release $a, Release $b): int {
            return $b->tag()->compare($a->tag());
        });

        return new self($sorted);
    }

    public function isEmpty(): bool
    {
        return [] === $this->values;
    }
}
