<?php
declare(strict_types=1);

namespace db;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class Migration
{
    /**
     * @var Builder
     */
    private $schema;

    /**
     * @param Builder $schema
     */
    public function __construct(Builder $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return bool
     */
    public function up() : bool
    {
        if ($this->schema->hasTable('articles')) {
            return false;
        }

        $this->schema->create('articles', function (Blueprint $blueprint) {
            $blueprint->integerIncrements('id');
            $blueprint->string('title', 128);
            $blueprint->string('announce', 200);
            $blueprint->longText('content');
            $blueprint->string('image_src')->nullable();
            $blueprint->dateTimeTz('published_at');
            $blueprint->char('hash', 40)->unique();
            $blueprint->timestamps();
        });

        return true;
    }

    /**
     * @return bool
     */
    public function rollback() : bool
    {
        $this->schema->dropAllTables();
        return true;
    }
}
