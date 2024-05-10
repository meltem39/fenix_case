<?php

namespace App\Repositories\UserRepositories;

use App\Models\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Repositories\EloquentBaseRepository;
//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */

    protected $model;
    public function __construct(User $model){
        $this->model = $model;

        parent::__construct($this->model);
    }


    public function registerUser($data)
    {
        $this->create($data);
        $user = $this->model->where("device_uuid", $data["device_uuid"])->first();
        return $user;
    }

    public function statusUp($user_id){
        $this->model->whereId($user_id)->update(["is_premium" => 1]);
        return true;
    }

    public function userDetail($user_id){
        $info = UserPackage::join("payments", "user_packages.payment_id", "=", "payments.id")
            ->where("user_packages.user_id", $user_id)
            ->where("user_packages.quota", "!=", 0)
            ->first();
        return $info;
    }
}
