<?php

namespace App\Repositories\UserChatRepositories;

interface UserChatRepositoryInterface {

    public function groupList($user_id);

    public function newChat($data);

    public function chatMessage($chat);

    public function chatDetail($chat_id);
}
