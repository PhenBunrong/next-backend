<?php

namespace App\Repositories;

use App\Entities\Post;
use App\Validators\PostValidator;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class PostRepository.
 *
 * @package namespace App\Repositories;
 */
class PostRepository extends BaseRepository
{
     /**
     * @var array
     */
    protected $fieldSearchable = [
        'title' => 'like',
        'conect' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PostValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * @throws Throwable
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        DB::beginTransaction();
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter();
        $post = parent::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);
        $date = now();

        DB::commit();

        return $this->parserResult($post);
    }
}
