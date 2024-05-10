<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserController extends BaseController
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository
    ){
        $this->userRepository = $userRepository;

    }
    public function login(Request $request)
    {
        $request->validate([
            'device_uuid' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('device_uuid', $request->device_uuid)->first();

        if (!$user) {
            $user = $this->userRepository->registerUser($request->all());
        }


        $success["token"] = $user->createToken('mobile', ['role:user'])->plainTextToken;


        if ($user["is_premium"]){ //TODO PREMİUM KULLANICININ DETAYLARI
            $user["is_premium"] = $this->userRepository->userDetail($user["id"]);
        }

        $success["user_detail"] = $user;
        return $this->sendResponse($success, 'Login successfully.');

    }

}
