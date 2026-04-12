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

final class Changes
{
    private EntryList $added;
    private EntryList $changed;
    private EntryList $deprecated;
    private EntryList $removed;
    private EntryList $fixed;
    private EntryList $security;

    private function __construct(
        EntryList $added,
        EntryList $changed,
        EntryList $deprecated,
        EntryList $removed,
        EntryList $fixed,
        EntryList $security
    ) {
        $this->added = $added;
        $this->changed = $changed;
        $this->deprecated = $deprecated;
        $this->removed = $removed;
        $this->fixed = $fixed;
        $this->security = $security;
    }

    public static function empty(): self
    {
        return new self(
            EntryList::empty(),
            EntryList::empty(),
            EntryList::empty(),
            EntryList::empty(),
            EntryList::empty(),
            EntryList::empty(),
        );
    }

    public static function create(
        EntryList $added,
        EntryList $changed,
        EntryList $deprecated,
        EntryList $removed,
        EntryList $fixed,
        EntryList $security
    ): self {
        return new self(
            $added,
            $changed,
            $deprecated,
            $removed,
            $fixed,
            $security,
        );
    }

    public function added(): EntryList
    {
        return $this->added;
    }

    public function changed(): EntryList
    {
        return $this->changed;
    }

    public function deprecated(): EntryList
    {
        return $this->deprecated;
    }

    public function removed(): EntryList
    {
        return $this->removed;
    }

    public function fixed(): EntryList
    {
        return $this->fixed;
    }

    public function security(): EntryList
    {
        return $this->security;
    }

    public function isEmpty(): bool
    {
        if (!$this->added->isEmpty()) {
            return false;
        }

        if (!$this->changed->isEmpty()) {
            return false;
        }

        if (!$this->deprecated->isEmpty()) {
            return false;
        }

        if (!$this->fixed->isEmpty()) {
            return false;
        }

        if (!$this->removed->isEmpty()) {
            return false;
        }

        if (!$this->security->isEmpty()) {
            return false;
        }

        return true;
    }
}
