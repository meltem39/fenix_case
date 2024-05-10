<?php

namespace App\Repositories\PackageRepositories;

use App\Models\Package;
use App\Repositories\EloquentBaseRepository;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class PackageRepository.
 */
class PackageRepository extends EloquentBaseRepository implements PackageRepositoryInterface
{

    protected $model;
    public function __construct(Package $model){
        $this->model = $model;

        parent::__construct($this->model);
    }

    public function list(){
        $list = $this->model->get();
        return $list;
    }
}
