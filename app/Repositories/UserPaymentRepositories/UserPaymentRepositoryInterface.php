<?php

namespace App\Repositories\UserPaymentRepositories;

interface UserPaymentRepositoryInterface{

    public function buyPackage($user_id, $info);
}
