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
    public function packages(){
        $list = $this->packageRepository->list();
        return $this->sendResponse($list, "package list");
    }

    public function usersPackageDetail(){
        $user_id = $this->loginUser()->id;
        $detail = $this->userPackageRepository->packageDetail($user_id);
        return $detail;

    }

    public function usersPackages(){
        $user_id = $this->loginUser()->id;
        $detail = $this->userPackageRepository->allPackage($user_id);
        if (count($detail))
            return $this->sendResponse($detail, "all packages.");
        return $this->sendNegativeResponse($detail, "you dont have any package.");
    }

    public function buyPackage(Request $request){
        if ($request->productId == 4){
            return $this->sendNegativeResponse("ERROR", "cant buy free packet.");
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
