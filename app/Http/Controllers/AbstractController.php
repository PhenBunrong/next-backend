<?php

namespace App\Http\Controllers;

use App\Criteria\WithTrashedCriteria;
use App\Enums\Pagination;
use App\Interfaces\Controllers\AbstractControllerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class AbstractController extends Controller implements AbstractControllerInterface
{
    protected string $fileKey = 'file';

    protected string $modelName = '';

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->setLimit($request->get('limit', '20'));
        $method = $request->input('method', Pagination::paginate);

        if ((int) $request->get('limit') === -1) {
            return $this->respond($this->repository->all());
        }

        if (! in_array($method, Pagination::getPaginates())) {
            $method = Pagination::paginate;
        }

        return $this->respond($this->repository->paginate($this->getLimit(), '*', $method));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $attributes = $request->all();
        $attributes[$this->fileKey] = null;
        $attributes['fileKey'] = $this->fileKey;
        if ($request->hasFile($this->fileKey)) {
            $attributes[$this->fileKey] = $request->file($this->fileKey);
        }
        if ($data = $this->repository->create($attributes)) {
            return $this->respondWithMessage($data, __('message.success.create', ['name' => __('module.'.$this->modelName)]));
        }
        $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respondBadRequest(__('message.failed.create', ['name' => __('module.'.$this->modelName)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        if ($request->get('withTrashed', false)) {
            $this->repository->pushCriteria(WithTrashedCriteria::class);
        }

        return $this->respond($this->repository->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $attributes = $request->all();
        $attributes[$this->fileKey] = null;
        $attributes['fileKey'] = $this->fileKey;
        if ($request->hasFile($this->fileKey)) {
            $attributes[$this->fileKey] = $request->file($this->fileKey);
        }
        if ($data = $this->repository->update($attributes, $id)) {
            return $this->respondWithMessage($data, __('message.success.update', ['name' => __('module.'.$this->modelName)]));
        }
        $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respondBadRequest(__('message.failed.update', ['name' => __('module.'.$this->modelName)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function destroy(string $id): JsonResponse
    {
        if ($data = $this->repository->delete($id)) {
            return $this->respondWithMessage($data, __('message.success.destroy', ['name' => __('module.'.$this->modelName)]));
        }
        $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respondBadRequest(__('message.failed.destroy', ['name' => __('module.'.$this->modelName)]));
    }
}
