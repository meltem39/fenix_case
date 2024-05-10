<?php

namespace App\Http\Controllers\API\Package;

use App\Http\Controllers\API\BaseController;
use App\Models\Payment;
use App\Repositories\UserPackageRepositories\UserPackageRepositoryInterface;
use App\Repositories\UserRepositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Repositories\PackageRepositories\PackageRepositoryInterface;
use App\Repositories\UserPaymentRepositories\UserPaymentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Laravel\Prompts\error;

class PackageController extends BaseController
{

    private PackageRepositoryInterface $packageRepository;
    private UserPaymentRepositoryInterface $userPaymentRepository;
    public function __construct(PackageRepositoryInterface $packageRepository,
                                UserPaymentRepositoryInterface $userPaymentRepository,
                                UserPackageRepositoryInterface $userPackageRepository,
                                UserRepositoryInterface $userRepository
    ){
        $this->packageRepository = $packageRepository;
        $this->userPaymentRepository = $userPaymentRepository;
        $this->userPackageRepository = $userPackageRepository;
        $this->userRepository = $userRepository;

    }
    /**
     * @OA\Get(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/packages",
     *     summary="Get a list of packages",
     *     tags={"Package"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function packages(){
        $list = $this->packageRepository->list();
        return $this->sendResponse($list, "package list");
    }

    public function usersPackageDetail(){
        $user_id = $this->loginUser()->id;
        $detail = $this->userPackageRepository->packageDetail($user_id);
        return $detail;

    }
    /**
     * @OA\Get(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/packages/list",
     *     summary="Get users packages",
     *     tags={"Package"},
     *     @OA\Response(response=200, description="Users package history"),
     *     @OA\Response(response=300, description="If users package history is empty"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function usersPackages(){
        $user_id = $this->loginUser()->id;
        $detail = $this->userPackageRepository->allPackage($user_id);
        if (count($detail))
            return $this->sendResponse($detail, "Package history.");
        return $this->sendNegativeResponse($detail, "You dont have any package.");
    }


    /**
     * @OA\Post(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/packages/subscription",
     *     summary="User purchase a package",
     *     tags={"Package"},
     *
     *     requestBody= @OA\RequestBody(
     *        request="package_body",
     *        description="Pet object that needs to be added to the store",
     *        required=true,
     *        @OA\JsonContent(
     *            type="object",
     *            @OA\Property (
     *                   type="string",
     *                   default="TEST TOKEN",
     *                   description="AppStore Receipt token is needed to fullfil payment request",
     *                   property="receiptToken"
     *           ),
     *            @OA\Property (
     *                   type="string",
     *                   default="3",
     *                   description="Product id is needed to determine which package is purchased.",
     *                   property="productId"
     *                  ),
     *       )
     *    ),
     *
     *     @OA\Response(response=200, description="Successful purchase"),
     *     @OA\Response(response=300, description="Can not buy free package"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */
    public function buyPackage(Request $request){
        if ($request->productId == 4){
            return $this->sendNegativeResponse("ERROR", "cant buy free package.");
        }
        $validation_control = Validator::make($request->all(), [
            "receiptToken" => "required|unique:payments",
            "productId" => "required",
        ]);

        if ($validation_control->fails())
            return $this->sendErrorValidation("Validation Error",$validation_control->errors());

        $user_id = $this->loginUser()->id;
        $buy = $this->userPaymentRepository->buyPackage($user_id, $request->all());
        if ($buy["status"]){
            $detail = $this->userPackageRepository->insertPackage($buy["data"]);
            $detail["payment_info"] = $buy["data"];
            $message = "positive";
            $user_status_up = $this->userRepository->statusUp($user_id);
            return $this->sendResponse($detail, $message);
        } else {
            $buy["package_detail"] = $this->usersPackageDetail();
            $message = "You cannot purchase another package before the quota of the package you purchased is exhausted.";
            return $this->sendNegativeResponse($buy, $message);
        }

    }


}
