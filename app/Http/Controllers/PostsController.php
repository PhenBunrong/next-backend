<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\Presenters\PostPresenter;
use App\Repositories\PostRepository;
use App\Http\Controllers\AbstractController;
use App\Interfaces\Controllers\PostControllerInterface;

/**
 * Class PostsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PostsController extends AbstractController implements PostControllerInterface
{
   protected string $modelName = 'post';
   
   public function __construct(protected readonly PostRepository $repository)
   {
        $this->repository->setPresenter(PostPresenter::class);
   }
}
