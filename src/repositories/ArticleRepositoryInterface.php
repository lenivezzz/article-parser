<?php
declare(strict_types=1);

namespace php_part\repositories;

use Illuminate\Database\Eloquent\Collection;
use php_part\models\Article;

interface ArticleRepositoryInterface
{
    /**
     * @param array $attributes
     * @return int
     */
    public function store(array $attributes) : int;

    /**
     * @param string $order
     * @return Collection
     */
    public function findAll(string $order = 'desc') : Collection;

    /**
     * @param int $id
     * @return Article
     */
    public function findById(int $id) : Article;
}
