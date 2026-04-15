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

final class MarkdownRenderer
{
    private MarkdownBuffer $markdownBuffer;

    public function __construct()
    {
        $this->markdownBuffer = new MarkdownBuffer();
    }

    public function renderChangelog(Changelog $changelog): string
    {
        $this->appendHeader();
        $this->appendUnreleased($changelog);
        $this->appendReleases($changelog);
        $this->appendFooter();

        return $this->markdownBuffer->flush();
    }

    /**
     * @throws UnknownRelease
     */
    public function renderRelease(
        Changelog $changelog,
        Tag $tag
    ): string {
        $previousReference = $changelog->repository()->initialCommit();

        $release = $changelog->releases()->releaseFor($tag);

        if (!$release instanceof Release) {
            throw UnknownRelease::named($tag);
        }

        $previousRelease = $changelog->releases()->previousReleaseFor($tag);

        if ($previousRelease instanceof Release) {
            $previousReference = $previousRelease->tag();
        }

        if ($previousReference instanceof Reference) {
            $this->markdownBuffer->append(
                self::forAFullDiffSee(
                    $changelog->repository(),
                    $previousReference,
                    $release->tag(),
                ),
                '',
            );
        }

        if (!$release->changes()->isEmpty()) {
            $this->appendChanges(
                $changelog->repository(),
                $release->changes(),
                2,
            );
        }

        $this->appendFooter();

        return $this->markdownBuffer->flush();
    }

    private function appendHeader(): void
    {
        $this->markdownBuffer->append(
            '# Changelog',
            '',
            'All notable changes to this project will be documented in this file.',
            '',
            'The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).',
            '',
        );
    }

    private function appendUnreleased(Changelog $changelog): void
    {
        $previousReference = $changelog->repository()->initialCommit();

        $previousRelease = $changelog->releases()->sortedByTagDescending()->first();

        if ($previousRelease instanceof Release) {
            $previousReference = $previousRelease->tag();
        }

        if (!$previousReference instanceof Reference) {
            return;
        }

        $this->markdownBuffer->append(
            '## Unreleased',
            '',
            self::forAFullDiffSee(
                $changelog->repository(),
                $previousReference,
                $changelog->repository()->defaultBranch(),
            ),
            '',
        );

        if ($changelog->unreleased()->changes()->isEmpty()) {
            return;
        }

        $this->appendChanges(
            $changelog->repository(),
            $changelog->unreleased()->changes(),
            3,
        );
    }

    private function appendReleases(Changelog $changelog): void
    {
        $initialCommit = $changelog->repository()->initialCommit();

        foreach ($changelog->releases()->sortedByTagDescending()->toArray() as $release) {
            $previousReference = $initialCommit;

            $previousRelease = $changelog->releases()->previousReleaseFor($release->tag());

            if ($previousRelease instanceof Release) {
                $previousReference = $previousRelease->tag();
            }

            $this->appendRelease(
                $changelog->repository(),
                $previousReference,
                $release,
            );
        }
    }

    private function appendRelease(
        Repository $repository,
        ?Reference $previousReference,
        Release $release
    ): void {
        $this->markdownBuffer->append(
            \sprintf(
                '## [`%s`](%s)',
                $release->tag()->toString(),
                self::urlToRelease(
                    $repository,
                    $release->tag(),
                ),
            ),
            '',
        );

        if ($previousReference instanceof Reference) {
            $this->markdownBuffer->append(
                self::forAFullDiffSee(
                    $repository,
                    $previousReference,
                    $release->tag(),
                ),
                '',
            );
        }

        if ($release->changes()->isEmpty()) {
            return;
        }

        $this->appendChanges(
            $repository,
            $release->changes(),
            3,
        );
    }

    private function appendChanges(
        Repository $repository,
        Changes $changes,
        int $sectionHeadingLevel
    ): void {
        /** @var array<string, EntryList> $sections */
        $sections = [
            'Added' => $changes->added(),
            'Changed' => $changes->changed(),
            'Deprecated' => $changes->deprecated(),
            'Fixed' => $changes->fixed(),
            'Removed' => $changes->removed(),
            'Security' => $changes->security(),
        ];

        foreach ($sections as $sectionName => $entryList) {
            if ($entryList->isEmpty()) {
                continue;
            }

            $this->markdownBuffer->append(
                \sprintf(
                    '%s %s',
                    \str_repeat(
                        '#',
                        $sectionHeadingLevel,
                    ),
                    $sectionName,
                ),
                '',
            );

            foreach ($entryList->sortedByPullRequestAscending()->toArray() as $entry) {
                $this->appendEntry(
                    $repository,
                    $entry,
                );
            }

            $this->markdownBuffer->append('');
        }
    }

    private function appendEntry(
        Repository $repository,
        Entry $entry
    ): void {
        $this->markdownBuffer->append(\sprintf(
            '- %s ([#%d](%s)), by [@%s](%s)',
            $entry->description()->toString(),
            $entry->pullRequest()->toInt(),
            self::urlToPullRequest(
                $repository,
                $entry->pullRequest(),
            ),
            $entry->author()->toString(),
            self::urlToAuthor($entry->author()),
        ));

        if (!$entry->notes() instanceof Notes) {
            return;
        }

        $this->markdownBuffer->append('');

        $lines = \explode(
            "\n",
            $entry->notes()->toString(),
        );

        $this->markdownBuffer->append(...\array_map(static function (string $line): string {
            return \sprintf(
                '  %s',
                $line,
            );
        }, $lines));

        $this->markdownBuffer->append('');
    }

    private function appendFooter(): void
    {
        $this->markdownBuffer->append(
            '',
            '<!-- generated by ergebnis/keep-a-changelog -->',
        );
    }

    private static function forAFullDiffSee(
        Repository $repository,
        Reference $from,
        Reference $to
    ): string {
        return \sprintf(
            'For a full diff see [`%s...%s`](%s).',
            $from->toString(),
            $to->toString(),
            self::urlToDiff(
                $repository,
                $from,
                $to,
            ),
        );
    }

    private static function urlToDiff(
        Repository $repository,
        Reference $from,
        Reference $to
    ): string {
        return \sprintf(
            'https://github.com/%s/%s/compare/%s...%s',
            $repository->owner()->toString(),
            $repository->name()->toString(),
            $from->toString(),
            $to->toString(),
        );
    }

    private static function urlToRelease(
        Repository $repository,
        Tag $tag
    ): string {
        return \sprintf(
            'https://github.com/%s/%s/releases/tag/%s',
            $repository->owner()->toString(),
            $repository->name()->toString(),
            $tag->toString(),
        );
    }

    private static function urlToPullRequest(
        Repository $repository,
        PullRequest $pullRequest
    ): string {
        return \sprintf(
            'https://github.com/%s/%s/pull/%d',
            $repository->owner()->toString(),
            $repository->name()->toString(),
            $pullRequest->toInt(),
        );
    }

    private static function urlToAuthor(Author $author): string
    {
        return \sprintf(
            'https://github.com/%s',
            $author->toString(),
        );
    }
}
