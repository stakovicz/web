<?php

declare(strict_types=1);

namespace AppBundle\Tests\TechLetter\Model;

use AppBundle\TechLetter\Model\Article;
use AppBundle\TechLetter\Model\News;
use AppBundle\TechLetter\Model\Project;
use AppBundle\TechLetter\Model\TechLetter;
use AppBundle\TechLetter\Model\TechLetterFactory;
use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TechLetterFactoryTest extends TestCase
{
    #[DataProvider('jsonDatProvider')]
    public function testCreateTechLetterFromJson(?string $jsonFilePath, TechLetter $expectedTechLetter): void
    {
        $json = null;

        if ($jsonFilePath !== null) {
            $json = file_get_contents($jsonFilePath);
            self::assertIsString($json);
        }

        $actualTechLetter = (new TechLetterFactory(new MapperBuilder()))->createTechLetterFromJson($json);

        self::assertEquals($expectedTechLetter, $actualTechLetter);
    }

    public static function jsonDatProvider(): Generator
    {
        yield 'null' => [
            'jsonFilePath' => null,
            'expectedTechLetter' => new TechLetter(),
        ];

        yield 'empty' => [
            'jsonFilePath' => __DIR__ . '/fixtures/empty.json',
            'expectedTechLetter' => new TechLetter(),
        ];

        yield 'keys-present-but-empty-values' => [
            'jsonFilePath' => __DIR__ . '/fixtures/keys-present-but-empty-values.json',
            'expectedTechLetter' => new TechLetter(),
        ];

        yield 'with-only-projects' => [
            'jsonFilePath' => __DIR__ . '/fixtures/with-only-projects.json',
            'expectedTechLetter' => new TechLetter(
                null,
                null,
                [],
                [
                    new Project('https://github.com/afup/planete', 'afup/planete', 'Le code source de planete-php.fr'),
                    new Project('https://github.com/afup/barometre', 'afup/barometre', 'Le code source de barometre.afup.org'),
                ],
            ),
        ];

        yield 'with-only-articles' => [
            'jsonFilePath' => __DIR__ . '/fixtures/with-only-articles.json',
            'expectedTechLetter' => new TechLetter(
                null,
                null,
                [
                    new Article('https://example.com/fr', 'Example en français', 'example.com', '2', 'Lorem ipsum', 'fr'),
                    new Article('https://example.com/en', 'Example with default language', 'example.com', '2', 'Lorem ipsum', 'en'),
                ],
                [],
            ),
        ];

        yield 'language-null' => [
            'jsonFilePath' => __DIR__ . '/fixtures/language-null.json',
            'expectedTechLetter' => new TechLetter(
                null,
                null,
                [
                    new Article('https://example.com', 'Example avec language à null', 'example.com', '2', 'Lorem ipsum', 'en'),
                ],
                [],
            ),
        ];

        yield 'with-only-first-news' => [
            'jsonFilePath' => __DIR__ . '/fixtures/with-only-first-news.json',
            'expectedTechLetter' => new TechLetter(
                new News(
                    'https://afup.org/news/1222-forum-php-2024-exceptionnel',
                    'Un Forum PHP 2024 exceptionnel !',
                    DateTimeImmutable::createFromFormat('!Y-m-d', '2024-10-21'),
                ),
                null,
                [],
                [],
            ),
        ];

        yield 'with-only-second-news' => [
            'jsonFilePath' => __DIR__ . '/fixtures/with-only-second-news.json',
            'expectedTechLetter' => new TechLetter(
                null,
                new News(
                    'https://afup.org/news/1231-enquete2025-barometre-des-salaires-PHP-ouverte',
                    'L’enquête 2025 du baromètre des salaires en PHP est ouverte',
                    DateTimeImmutable::createFromFormat('!Y-m-d', '2025-03-17'),
                ),
                [],
                [],
            ),
        ];

        yield 'full' => [
            'jsonFilePath' => __DIR__ . '/fixtures/full.json',
            'expectedTechLetter' => new TechLetter(
                new News(
                    'https://afup.org/news/1222-forum-php-2024-exceptionnel',
                    'Un Forum PHP 2024 exceptionnel !',
                    DateTimeImmutable::createFromFormat('!Y-m-d', '2024-10-21'),
                ),
                new News(
                    'https://afup.org/news/1231-enquete2025-barometre-des-salaires-PHP-ouverte',
                    'L’enquête 2025 du baromètre des salaires en PHP est ouverte',
                    DateTimeImmutable::createFromFormat('!Y-m-d', '2025-03-17'),
                ),
                [
                    new Article('https://example.com/fr', 'Example en français', 'example.com', '2', 'Lorem ipsum', 'fr'),
                    new Article('https://example.com/en', 'Example with default language', 'example.com', '2', 'Lorem ipsum', 'en'),
                ],
                [
                    new Project('https://github.com/afup/planete', 'afup/planete', 'Le code source de planete-php.fr'),
                    new Project('https://github.com/afup/barometre', 'afup/barometre', 'Le code source de barometre.afup.org'),
                ],
            ),
        ];
    }
}
