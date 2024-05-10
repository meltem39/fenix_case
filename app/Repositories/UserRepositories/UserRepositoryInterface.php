<?php

namespace App\Repositories\UserRepositories;

interface UserRepositoryInterface {


    public function registerUser($data);

    public function statusUp($user_id);

    public function userDetail($user_id);
}
