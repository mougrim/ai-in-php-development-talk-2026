<?php
declare(strict_types=1);

readonly class TagToSubTagsSplitter
{
    /**
     * @return list<string>
     */
    public function split(string $tag): array
    {
        $words = explode(' ', $tag);
        $wordCount = count($words);
        $subTagWordGroups = [];
        if ($wordCount <= 1) {
            return [];
        }

        $subTagWordGroups[] = $words;

        // Generate sub tags with words count from 2 to $wordCount - 1
        for ($start = 0; $start < $wordCount; $start++) {
            for ($end = $start + 1; $end < $wordCount && !($start === 0 && $end === $wordCount - 1); $end++) {
                // Make sub tag words from current range
                $subTagWords = [];
                for ($i = $start; $i <= $end; $i++) {
                    $subTagWords[] = $words[$i];
                }
                $subTagWordGroups[] = $subTagWords;
            }
        }

        // move more long groups up
        usort($subTagWordGroups, static function (array $subTagWords1, array $subTagWords2): int {
            return count($subTagWords2) <=> count($subTagWords1);
        });

        return array_map(
            static fn(array $subTagWords): string => implode(' ', $subTagWords),
            $subTagWordGroups,
        );
    }
}
