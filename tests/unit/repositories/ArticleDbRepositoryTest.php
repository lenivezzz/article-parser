<?php
declare(strict_types=1);

namespace tests\unit\repositories;

use Faker\Factory;
use php_part\exceptions\RepositoryException;
use php_part\models\Article;
use php_part\repositories\ArticleDbRepository;
use tests\unit\DbTestCase;
use Throwable;

class ArticleDbRepositoryTest extends DbTestCase
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function testStore() : void
    {
        $repository = new ArticleDbRepository();
        $attributes = [
            'title' => Factory::create()->words(5, true),
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => Factory::create()->unique()->sha1,
        ];
        $id = $repository->store($attributes);
        $article = Article::query()->findOrFail($id);
        $this->assertEquals(array_values($attributes), [
            $article->title,
            $article->announce,
            $article->content,
            $article->published_at,
            $article->hash,
        ]);

        $this->expectException(RepositoryException::class);
        $repository->store($attributes);
    }

    /**
     * @inheritDoc
     */
    public function testFindAll() : void
    {
        $article = new Article([
            'title' => Factory::create()->words(5, true),
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => Factory::create()->unique()->sha1,
        ]);
        $article->save();
        $articleList = (new ArticleDbRepository())->findAll();
        $this->assertTrue($articleList->contains($article));
    }
}
