<?php
declare(strict_types=1);

readonly class ParentTagNamesResolver
{
    /**
     * @param list<string> $multiWordTags
     * @param list<list<string>> $synonymTagGroups
     */
    public function __construct(
        private TagToSubTagsSplitter $tagToSubTagsSplitter,
        private array $multiWordTags,
        private array $synonymTagGroups,
    ) {
    }

    /**
     * @return array<string>
     */
    public function resolve(string $tag): array
    {
        $subTags = $this->tagToSubTagsSplitter->split($tag);
        $multiWordTags = array_intersect($this->multiWordTags, $subTags);
        usort(
            $multiWordTags,
            static fn(
                string $multiWordTag1,
                string $multiWordTag2,
            ): int => substr_count($multiWordTag2, ' ') <=> substr_count($multiWordTag1, ' '),
        );
        $resultMultiWordTags = [];
        $oneWordTags = explode(' ', $tag);
        foreach ($multiWordTags as $multiWordTag) {
            $multiWordTagParts = explode(' ', $multiWordTag);
            $firstPosition = null;
            $currentPartPosition = 0;
            foreach ($oneWordTags as $position => $oneWordTag) {
                if ($multiWordTagParts[$currentPartPosition] === $oneWordTag) {
                    if ($firstPosition === null) {
                        $firstPosition = $position;
                    }
                    $currentPartPosition++;
                    if (!isset($multiWordTagParts[$currentPartPosition])) {
                        array_splice($oneWordTags, $firstPosition, count($multiWordTagParts));
                        $resultMultiWordTags[] = $multiWordTag;
                        break;
                    }
                } else {
                    $currentPartPosition = 0;
                    $firstPosition = null;
                }
            }
        }
        $tags = array_merge($resultMultiWordTags, $oneWordTags);
        $tagGroups = [
            $tags,
        ];
        foreach ($this->synonymTagGroups as $synonymTags) {
            if (array_intersect($tags, $synonymTags)) {
                $tagGroups[] = $synonymTags;
            }
        }
        $tags = array_merge(...$tagGroups);

        return array_values(array_unique(array_diff($tags, [$tag])));
    }
}
