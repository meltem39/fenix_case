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
    /**
     * @OA\Get(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/chats",
     *     summary="Get users chat group list",
     *     tags={"Chat"},
     *     @OA\Response(response=200, description="Returns Chat Group List"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function chatGroupList(){
        $user_id = $this->loginUser()->id;
        $group_list = $this->userChatRepository->groupList($user_id);
        return $this->sendResponse($group_list, "chat group list");
    }
    /**
     * @OA\Get(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/chats/{chat_id}",
     *     summary="Get users specific chat details",
     *     tags={"Chat"},
     *     @OA\Parameter(
     *           name="chat_id",
     *           description="Chat Id needed to retrieve details",
     *           required=true,
     *           in="path",
     *           @OA\Schema(
     *               type="integer"
     *           )
     *       ),
     *     @OA\Response(response=200, description="Returns chat details based on chat id"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function userChatDetail($chat_id) {
        $chats = $this->userChatRepository->chatDetail($chat_id);
        return $this->sendResponse($chats, "chat details");
    }

    /**
     * @OA\Post(
     *     security={{"bearerAuth": {}}},
     *     path="/api/user/chats",
     *     summary="Create a new chat if no chat ids retrievable in the request. If exist insert new chat message into the specified chat group",
     *     tags={"Chat"},
     *     requestBody= @OA\RequestBody(
     *         request="chats_body",
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property (
     *                    type="text",
     *                    default="Default Meesage",
     *                    description="Chat message needed to post into chat",
     *                    property="message"
     *            ),
     *             @OA\Property (
     *                    type="string",
     *                    default="1",
     *                    description="Chat Id needed to post a new message to the related chat. If not given create a new chat group.",
     *                    property="chatId"
     *                   ),
     *        )
     *     ),
     *     @OA\Response(response=200, description="Returns chats answer"),
     *     @OA\Response(response=300, description="LIMIT IS FULL"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function chatMessage(Request $request) {
        $data["user_id"] = $this->loginUser()->id;
        $data["message"] = $request->message;
        $chat_id = $request->chatId;
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
