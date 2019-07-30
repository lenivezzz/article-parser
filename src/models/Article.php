<?php
declare(strict_types=1);

namespace php_part\models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $fillable = [
        'title',
        'announce',
        'content',
        'image_src',
        'published_at',
        'hash',
    ];
}
