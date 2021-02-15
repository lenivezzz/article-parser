<?php
declare(strict_types=1);

namespace tests\unit\repositories;

use Faker\Factory;
use php_part\exceptions\RepositoryException;
use php_part\models\Article;
use php_part\repositories\ArticleDbRepository;
use tests\unit\DbTestCase;

class ArticleDbRepositoryTest extends DbTestCase
{

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
        self::assertEquals(array_values($attributes), [
            $article->title,
            $article->announce,
            $article->content,
            $article->published_at,
            $article->hash,
        ]);

        $this->expectException(RepositoryException::class);
        $repository->store($attributes);
    }

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
        self::assertEquals($article->id, $articleList[0]->id);

        $lastArticle = new Article([
            'title' => Factory::create()->words(5, true),
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => Factory::create()->unique()->sha1,
        ]);
        $lastArticle->save();

        $articleList = (new ArticleDbRepository())->findAll(1);
        self::assertCount(1, $articleList);
        self::assertEquals($articleList[0]->id, $lastArticle->id);
    }

    public function testFindById() : void
    {
        $title = Factory::create()->words(5, true);
        $article = new Article([
            'title' => $title,
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => Factory::create()->unique()->sha1,
        ]);
        $article->save();

        self::assertEquals($title, (new ArticleDbRepository())->findById($article->id)->title);
    }

    public function testFindAllByHashList() : void
    {
        self::assertEquals([], (new ArticleDbRepository())->findAllByHashList([]));
        $hash = str_repeat('z', 40);
        $article = new Article([
            'title' => Factory::create()->words(5, true),
            'announce' => Factory::create()->text(200),
            'content' => Factory::create()->randomHtml(),
            'published_at' => Factory::create()->date('Y-m-d H:i:s'),
            'hash' => $hash,
        ]);
        $article->save();

        $articleList = (new ArticleDbRepository())->findAllByHashList([$hash]);
        self::assertCount(1, $articleList);
        self::assertEquals($hash, $articleList[0]->hash);
    }
}
