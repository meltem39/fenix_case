<?php

namespace App\Providers;

use App\Models\ChatGroup;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserChat;
use App\Models\UserPackage;
use App\Repositories\ChatGroupRepositories\ChatGroupRepository;
use App\Repositories\ChatGroupRepositories\ChatGroupRepositoryInterface;
use App\Repositories\PackageRepositories\PackageRepository;
use App\Repositories\PackageRepositories\PackageRepositoryInterface;
use App\Repositories\UserChatRepositories\UserChatRepository;
use App\Repositories\UserChatRepositories\UserChatRepositoryInterface;
use App\Repositories\UserPackageRepositories\UserPackageRepository;
use App\Repositories\UserPackageRepositories\UserPackageRepositoryInterface;
use App\Repositories\UserPaymentRepositories\UserPaymentRepository;
use App\Repositories\UserPaymentRepositories\UserPaymentRepositoryInterface;
use App\Repositories\UserRepositories\UserRepository;
use App\Repositories\UserRepositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(UserRepositoryInterface::class, function($app) { return new UserRepository(new User()); });
        $this->app->bind(PackageRepositoryInterface::class, function ($app){ return new PackageRepository(new Package()); } );
        $this->app->bind(UserPaymentRepositoryInterface::class, function ($app){ return new UserPaymentRepository(new Payment()); } );
        $this->app->bind(UserPackageRepositoryInterface::class, function ($app){ return new UserPackageRepository(new UserPackage()); } );
//        $this->app->bind(ChatGroupRepositoryInterface::class, function ($app){ return new ChatGroupRepository(new ChatGroup()); });
        $this->app->bind(UserChatRepositoryInterface::class, function ($app){ return new UserChatRepository(new UserChat(), new ChatGroup()); });
    }
}
