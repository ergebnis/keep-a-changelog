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
     *
     * @throws InvalidReleaseList
     */
    private function __construct(array $values)
    {
        \usort($values, static function (Release $a, Release $b): int {
            return $a->tag()->compare($b->tag());
        });

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
}
