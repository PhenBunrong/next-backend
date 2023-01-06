<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Transformers\PostTransformer;
use League\Fractal\TransformerAbstract;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PostPresenter.
 */
class PostPresenter extends BasePresenter
{
    /**
     * Transformer.
     *
     * @return BasePresenter|TransformerAbstract
     */
    public function getTransformer(): BasePresenter|TransformerAbstract
    {
        return new PostTransformer();
    }
}
