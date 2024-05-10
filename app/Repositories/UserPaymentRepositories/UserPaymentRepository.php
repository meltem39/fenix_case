<?php

namespace App\Repositories\UserPaymentRepositories;

use App\Models\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Repositories\EloquentBaseRepository;
use Carbon\Carbon;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class UserPaymentRepository.
 */
class UserPaymentRepository extends EloquentBaseRepository implements UserPaymentRepositoryInterface
{

    protected $model;
    public function __construct(Payment $model){
    $this->model = $model;

    parent::__construct($this->model);
    }

    public function buyPackage($user_id, $info){
        date_default_timezone_set('Etc/GMT-3');
        $check_package = UserPackage::where("user_id", $user_id)->where("quota", "!=", 0)->first();
        // User'a tanımlı pakette kullanım limiti varsa satın alma başarısız olur.
        if (!$check_package){
            $info["user_id"] = $user_id;
            $info["purchase_date"] = Carbon::now()->toDateString();
            $info["status"] = 1;
            $insert = $this->model->create($info);
            return ["status" => true, "data" => $insert];
        } else {
            $info["user_id"] = $user_id;
            $info["purchase_date"] = Carbon::now()->toDateString();
            $info["status"] = "0";
            $insert = $this->model->create($info);
            return ["status" => false];
        }

    }
}
