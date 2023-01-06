<?php

namespace App\Repositories;

use Throwable;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Eloquent\BaseRepository as BaseRepo;

abstract class BaseRepository extends BaseRepo implements CacheableInterface
{
    use CacheableRepository {
        serializeCriteria as protected serializeCriteriaOverride;
    }

    /**
     * Delete an entity in repository by id.
     *
     * @param $id
     * @return bool|int|mixed
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    public function delete($id): mixed
    {
        DB::beginTransaction();
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter();

        $model = $this->find($id);
        $originalModel = clone $model;

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        if ($model->delete()) {
            event(new RepositoryEntityDeleted($this, $originalModel));

            DB::commit();

            return $this->parserResult($originalModel);
        }

        DB::rollBack();

        return false;
    }
}
