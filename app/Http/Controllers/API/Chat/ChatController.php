<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\ChatGroupRepositories\ChatGroupRepositoryInterface;
use App\Repositories\UserChatRepositories\UserChatRepositoryInterface;
use Illuminate\Http\Request;

class ChatController extends BaseController
{
    private UserChatRepositoryInterface $userChatRepository;
    public function __construct(UserChatRepositoryInterface $userChatRepository
    ){
        $this->userChatRepository = $userChatRepository;
    }

    public function chatGroupList(){
        $user_id = $this->loginUser()->id;
        $group_list = $this->userChatRepository->groupList($user_id);
        return $this->sendResponse($group_list, "chat group list");
    }

    public function userChatDetail($chat_id) {
        $chats = $this->userChatRepository->chatDetail($chat_id);
        return $this->sendResponse($chats, "chat details");
    }


    public function chatMessage(Request $request) {
        $chat_id = $request->chatId;
        $data["user_id"] = $this->loginUser()->id;
        $data["message"] = $request->message;
        if ($chat_id){
            $data["chat_group_id"] = $chat_id;
            $chat = $this->userChatRepository->chatMessage($data);
            if ($chat["status"]){
                return $this->sendResponse($chat["data"]["answer"], "OK");
            } else {
                return $this->sendNegativeResponse("LIMIT IS FULL", "If you use this chat, you have to buy new packet");
            }
        }else {
            $chat_group = $this->userChatRepository->newChat($data);
            if (!$chat_group["status"])
                return $this->sendNegativeResponse("LIMIT IS FULL", $chat_group["detail"]);
            $data["chat_group_id"] = $chat_group["detail"]["id"];
            $chat = $this->userChatRepository->chatMessage($data);
            if ($chat["status"]){
                return $this->sendResponse($chat["data"]["answer"], "OK");
            } else {
                return $this->sendNegativeResponse("LIMIT IS FULL", "If you use this chat, you have to buy new packet");
            }
        }
    }
}
