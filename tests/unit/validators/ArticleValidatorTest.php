<?php
declare(strict_types=1);

namespace tests\unit\validators;

use Faker\Factory;
use php_part\models\Article;
use php_part\validators\ArticleValidator;
use Symfony\Component\Validator\Validation;
use tests\unit\DbTestCase;

class ArticleValidatorTest extends DbTestCase
{
    public function testFails() : void
    {
        $validator = new ArticleValidator(Validation::createValidator());
        self::assertTrue($validator->fails([]));

        $attributes = [
            'title' => Factory::create()->words(5, true),
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => Factory::create()->unique()->sha1,
        ];

        self::assertFalse($validator->fails($attributes));

        $attributes['announce'] = str_repeat('a', 201);
        self::assertTrue($validator->fails($attributes));

        $attributes['announce'] = str_repeat('a', 200);
        self::assertFalse($validator->fails($attributes));

        $attributes['image_src'] = Factory::create()->word;
        self::assertTrue($validator->fails($attributes));

        $attributes['image_src'] = Factory::create()->url;
        self::assertFalse($validator->fails($attributes));

        Article::query()->create($attributes);
        self::assertTrue($validator->fails($attributes));
    }
}
