<?php
declare(strict_types=1);

#[CoversClass(TagPermalinkGenerator::class)]
class TagPermalinkGeneratorTest extends TestCase
{
    private readonly TagPermalinkGenerator $generator;

    public static function providerGenerate(): array
    {
        return [
            'Empty string' => [
                'name' => '',
                'expectedResult' => '',
            ],
            'Lowercase word' => [
                'name' => 'hello',
                'expectedResult' => 'hello',
            ],
            'Uppercase word' => [
                'name' => 'HELLO',
                'expectedResult' => 'hello',
            ],
            'Mixed case word' => [
                'name' => 'HeLLo',
                'expectedResult' => 'hello',
            ],
            'Words with space' => [
                'name' => 'hello world',
                'expectedResult' => 'hello-world',
            ],
            'Words with multiple spaces' => [
                'name' => 'hello  world',
                'expectedResult' => 'hello-world',
            ],
            'Underscore preserved' => [
                'name' => 'hello_world',
                'expectedResult' => 'hello_world',
            ],
            'Numbers preserved' => [
                'name' => 'hello123',
                'expectedResult' => 'hello123',
            ],
            'Leading special chars trimmed' => [
                'name' => '---hello',
                'expectedResult' => 'hello',
            ],
            'Trailing special chars trimmed' => [
                'name' => 'hello---',
                'expectedResult' => 'hello',
            ],
            'Leading and trailing special chars trimmed' => [
                'name' => '---hello---',
                'expectedResult' => 'hello',
            ],
            'Special characters replaced' => [
                'name' => 'hello!@#world',
                'expectedResult' => 'hello-world',
            ],
            'Non-ASCII characters replaced' => [
                'name' => 'привет',
                'expectedResult' => '',
            ],
            'Mixed case with spaces and special chars' => [
                'name' => 'Hello, World!',
                'expectedResult' => 'hello-world',
            ],
        ];
    }

    #[DataProvider('providerGenerate')]
    public function testGenerate(string $name, string $expectedResult): void
    {
        self::assertSame($expectedResult, $this->generator->generate($name));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new TagPermalinkGenerator();
    }
}
