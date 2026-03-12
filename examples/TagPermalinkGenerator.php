<?php
declare(strict_types=1);

readonly class TagPermalinkGenerator
{
    public function generate(string $name): string
    {
        $permalink = strtolower($name);
        $permalink = preg_replace("/([^a-z0-9_]+)/", '-', $permalink) ?? '';

        return trim($permalink, '-');
    }
}
