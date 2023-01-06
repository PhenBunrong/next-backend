<?php

namespace App\Transformers;

use App\Entities\Post;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

/**
 * Class PostTransformer.
 *
 * @package namespace App\Transformers;
 */
class PostTransformer extends BaseTransformer
{
    /**
     * Transform the Post entity.
     *
     * @param \App\Entities\Post $model
     *
     * @return array
     */
    public function transform(Post $model): array
    {
        return $this->addTimesHumanReadable($model, $model->toArray());
    }
}
