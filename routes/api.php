<?php

use App\Http\Controllers\WEB\Auth\AdminController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Package\PackageController;
use App\Http\Controllers\API\Chat\ChatController;
use Illuminate\Support\Facades\Route;



Route::post('user/login', [UserController::class, 'login']);
// Only for users
Route::middleware(['auth:sanctum', 'type.user'])->group(function () {
    Route::group(["namespace"=>"user", "prefix"=>"user"], function () {
        Route::group(["namespace"=>"packages", "prefix"=>"packages"], function () {

        Route::get('/', [PackageController::class, "packages"]);
        Route::get("list", [PackageController::class, "usersPackages"]);
        Route::post('subscription', [PackageController::class, "buyPackage"]);
        });

        Route::group(["namespace"=>"chats", "prefix"=>"chats"], function () {

            Route::get('/', [ChatController::class, "chatGroupList"]);
            Route::get("{chat_id}", [ChatController::class, "userChatDetail"]);
            Route::post('/', [ChatController::class, "chatMessage"]);
        });
    });
});


