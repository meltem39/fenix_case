<?php

namespace App\Repositories\UserPackageRepositories;

use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use App\Repositories\EloquentBaseRepository;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use function Symfony\Component\Translation\t;

//use Your Model

/**
 * Class UserPackageRepository.
 */
class UserPackageRepository extends EloquentBaseRepository implements UserPackageRepositoryInterface
{
    protected $model;
    public function __construct(UserPackage $model){
        $this->model = $model;

        parent::__construct($this->model);
    }


    public function requestValue($data){
        $req["user_id"] = $data["user_id"];
        $req["quota"] = Package::whereId($data["productId"])->first()->quota;
        $req["payment_id"] = $data["id"];
        return $req;
    }


    public function insertPackage($data){
        $req = $this->requestValue($data);
        $insert = $this->model->create($req);
        return $insert;
    }

    public function packageDetail($user_id){
        $user_package = $this->model->where("user_id", $user_id)->where("quota", "!=", 0)->first();
        return $user_package;
    }

    public function allPackage($user_id){
        $user_package = $this->model->where("user_id", $user_id)->orderByDesc("id")->get();
        return $user_package;
    }
}
