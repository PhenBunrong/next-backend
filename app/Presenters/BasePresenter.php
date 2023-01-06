<?php

namespace App\Presenters;

use Exception;
use League\Fractal\Resource\Item;
use Illuminate\Pagination\Paginator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceAbstract;
use Prettus\Repository\Presenter\FractalPresenter;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

abstract class BasePresenter extends FractalPresenter
{
    /**
     * BasePresenter constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->resourceKeyCollection = 'data';
        $this->resourceKeyItem = 'data';
    }

    /**
     * {@inheritdoc}
     */
    protected function transformItem($data): ResourceAbstract|Item
    {
        return $this->addAvailableIncludeToMeta(parent::transformItem($data));
    }

    /**
     * @param  ResourceAbstract  $resource
     * @return ResourceAbstract
     */
    private function addAvailableIncludeToMeta(ResourceAbstract $resource): ResourceAbstract
    {
        $resource->setMeta([
            'include' => $this->getTransformer()->getAvailableIncludes(),
        ]);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    protected function transformCollection($data): ResourceAbstract|Collection
    {
        return $this->addAvailableIncludeToMeta(parent::transformCollection($data));
    }

    /**
     * {@inheritdoc}
     */
    protected function transformPaginator($paginator): ResourceAbstract|Collection
    {
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $this->getTransformer(), $this->resourceKeyCollection);
        if ($paginator instanceof Paginator) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

            return $this->addAvailableIncludeToMeta($resource);
        }

        return $this->addAvailableIncludeToMeta(parent::transformPaginator($paginator));
    }
}
