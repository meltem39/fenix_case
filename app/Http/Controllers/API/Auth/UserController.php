<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class UserController extends BaseController
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository
    ){
        $this->userRepository = $userRepository;

    }
    /**
     * @OA\Post(
     *     path="/api/user/login",
     *     summary="User login/register via device uuid/name",
     *     tags={"Authentication"},
     *     requestBody= @OA\RequestBody(
     *       request="body",
     *       description="Pet object that needs to be added to the store",
     *       required=true,
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property (
     *                  type="string",
     *                  default="315315",
     *                  description="Device UUID is needed for unique identification for the current user",
     *                  property="device_uuid"
     *          ),
     *           @OA\Property (
     *                  type="string",
     *                  default="TEMP NAME",
     *                  description="device_name is needed if device is being registered for the first time.",
     *                  property="device_name"
     *                 ),
     *      )
     *   ),
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */
    public function login(Request $request)
    {

        $user = User::where('device_uuid', $request->device_uuid)->first();

        if (!$user) {
            $validation_control = Validator::make($request->all(),[
                'device_uuid' => 'required|unique:users',
                'device_name' => 'required',
            ]);

            if ($validation_control->fails())
                return $this->sendErrorValidation("Validation Error",$validation_control->errors());
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
