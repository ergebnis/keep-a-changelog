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

final class MarkdownBuffer
{
    /**
     * @var list<string>
     */
    private array $lines = [];

    public function append(string ...$lines): void
    {
        foreach ($lines as $line) {
            $this->lines[] = $line;
        }
    }

    public function flush(): string
    {
        $filtered = [];

        $count = \count($this->lines);

        $previous = null;

        for ($i = 0; $i < $count; ++$i) {
            $line = \rtrim($this->lines[$i]);

            if ('' === $line) {
                if (null === $previous) {
                    continue;
                }

                for (++$i; $i < $count; ++$i) {
                    $next = \rtrim($this->lines[$i]);

                    if ('' === $next) {
                        continue;
                    }

                    $filtered[] = $line;
                    $filtered[] = $next;

                    break;
                }

                continue;
            }

            $filtered[] = $line;

            $previous = $line;
        }

        $this->lines = [];

        return \implode('', \array_map(static function (string $line): string {
            return \sprintf(
                "%s\n",
                $line,
            );
        }, $filtered));
    }
}
