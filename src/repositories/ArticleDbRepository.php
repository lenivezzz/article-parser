<?php
declare(strict_types=1);

namespace php_part\repositories;

use Illuminate\Database\Eloquent\Collection;
use php_part\models\Article;
use Throwable;

class ArticleDbRepository implements ArticleRepositoryInterface
{

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function store(array $attributes) : int
    {
        $article = new Article([
            'title' => $attributes['title'],
            'announce' => $attributes['announce'],
            'image_src' => $attributes['image_src'] ?? null,
            'content' => $attributes['content'],
            'published_at' => $attributes['published_at'],
            'hash' => $attributes['hash'],
        ]);

        $article->saveOrFail();
        return $article->id;
    }

    /**
     * @inheritDoc
     */
    public function findAll() : Collection
    {
        return Article::query()->get();
    }
}
