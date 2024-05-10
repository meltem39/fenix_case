<?php

namespace App\Repositories\UserChatRepositories;

use App\Models\ChatGroup;
use App\Models\Package;
use App\Models\User;
use App\Models\UserChat;
use App\Models\UserPackage;
use App\Repositories\EloquentBaseRepository;
use Carbon\Carbon;
use Faker\Factory;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class UserChatRepository.
 */
class UserChatRepository extends EloquentBaseRepository implements UserChatRepositoryInterface
{
    protected $model;
    protected $chatGroupModel;
    public function __construct(UserChat $model, ChatGroup $chatGroupModel){
        $this->model = $model;
        $this->chatGroupModel = $chatGroupModel;

        parent::__construct($this->model, $this->chatGroupModel);
    }


    public function userPackage($user_id, $up=0){
        if($up){
            $package = UserPackage::where("user_id",$user_id)->where("quota", "!=", 0);
            $math = $package->first();
            if (isset($math) && $math->quota-1>0){
                $package->where("quota", ">", 0)->update(["quota" => $math->quota-1]);
                $info = UserPackage::whereId($package->first()->id)->select("id", "quota")->first();
                return ["status" => true, "user_package" => $info];
            } else if(isset($math) && $math->quota-1 == 0){
                $package->where("quota", 1)->update(["quota" => $math->quota-1, "quota_completion_date" => Carbon::now()->toDateString()]);
                User::whereId($user_id)->update(["is_premium" => "0"]);
                $info = UserPackage::where("user_id",$user_id)->where("quota", 0)->orderByDesc("id")->select("id", "quota")->first();
                return ["status" => true, "user_package" => $info];
            } else {
                return ["status" => false];
            }

        } else {
            $package = UserPackage::where("user_id",$user_id);
            if(($package->first())){
                $detail = $package->where("quota", "!=", 0)->first();
            } else {
                $data["user_id"] = $user_id;
                $data["quota"] = Package::whereId(4)->first()->quota;
                $detail = UserPackage::create($data);
            }
            return $detail;
        }
    }

    public function groupList($user_id){
        $list = $this->chatGroupModel->where("user_id", $user_id)->get();
        return $list;
    }

    public function newChat($data){
        $package_check = $this->userPackage($data["user_id"]);
        if (!isset($package_check))
            return ["status" => false, "detail" => "Your free usage quota has been reached."];
        $data["user_package_id"] = $package_check["id"];
        $data["title"] = $data["message"];
        $chat_group_create = $this->chatGroupModel->create($data);
        return ["status" => true, "detail" => $chat_group_create];
    }

    public function chatMessage($chat){
        $user_package = $this->userPackage($chat["user_id"], $up=1);
        if (!$user_package["status"]){
            return $user_package;
        } else {
            $chat["answer"] = Factory::create()->text;
            $insert = $this->model->create($chat);
            $user_package["data"] = $insert;
            return $user_package;
        }

    }

    public function chatDetail($chat_id){
        $list = $this->model->where("chat_group_id", $chat_id)->select("id","message", "answer")->orderByDesc("id")->get();
        return $list;
    }
}
