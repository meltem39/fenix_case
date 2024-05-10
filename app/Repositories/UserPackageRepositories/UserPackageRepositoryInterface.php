<?php

namespace App\Repositories\UserPackageRepositories;

interface UserPackageRepositoryInterface{

    public function insertPackage($data);

    public function packageDetail($user_id);

    public function allPackage($user_id);
}
