<?php

namespace App\Repositories\ChatGroupRepositories;

use App\Models\ChatGroup;
use App\Repositories\EloquentBaseRepository;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ChatGroupRepository.
 */
class ChatGroupRepository extends EloquentBaseRepository implements ChatGroupRepositoryInterface
{
    protected $model;
    public function __construct(ChatGroup $model){
        $this->model = $model;

        parent::__construct($this->model);
    }
}
