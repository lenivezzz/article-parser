<?php
declare(strict_types=1);

namespace php_part\repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use php_part\exceptions\RepositoryException;
use php_part\models\Article;
use Throwable;

class ArticleDbRepository implements ArticleRepositoryInterface
{

    /**
     * @inheritDoc
     * @throws RepositoryException
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
        try {
            $article->saveOrFail();
        } catch (Throwable $e) {
            throw new RepositoryException($e->getMessage(), 0, $e);
        }
        return $article->id;
    }

    /**
     * @inheritDoc
     */
    public function findAll(?int $limit = null, string $order = 'desc') : array
    {
        $builder = Article::query()->orderBy('id', $order);
        $limit !== null && $builder->limit($limit);
        return $builder->get()->all();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id) : Article
    {
        try {
            /** @var Article $article */
            $article = Article::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException($e->getMessage(), 0, $e);
        }

        return $article;
    }

    /**
     * @inheritDoc
     */
    public function findAllByHashList(array $hashList) : array
    {
        return Article::query()->whereIn('hash', $hashList)->get()->all();
    }
}
