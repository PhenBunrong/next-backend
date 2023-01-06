<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Post.
 *
 * @package namespace App\Entities;
 */
class Post extends Model
{
    protected $fillable = [
        'title',
        'content',  
    ];

    protected $casts = [
        'title' => 'string',
        'content' => 'string',
    ];
}
