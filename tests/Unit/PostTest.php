<?php

namespace Tests\Unit;

use App\Models\ChatGroup;
use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use Carbon\Carbon;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_add_post()
    {
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),

        ];

        $this->post("/api/user/login", $data)
            ->assertStatus(200);
    }

    public function test_add_post2()
    {
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),

        ];

        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('get', 'api/user/packages')->assertStatus(200);

    }
    public function test_add_post3()
    {
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),
        ];
        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];

        $package = [
            "name" => fake()->name,
            "fee" => rand(1,1000),
            "currency_unit" => "TL",
            "quota" => rand(1,100),
            "description" => fake()->text
        ];

        Package::create($package);

        $user_id = $response["data"]["user_detail"]["id"];
        $item = [
            "user_id" => $user_id,
            "receiptToken"=> fake()->text,
            "productId" => "1"
        ];

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('POST', 'api/user/packages/subscription', $item)->assertStatus(200);

    }

    public function test_add_post4(){
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),
        ];
        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];

        $package = [
            "name" => fake()->name,
            "fee" => rand(1,1000),
            "currency_unit" => "TL",
            "quota" => rand(1,100),
            "description" => fake()->text
        ];

        Package::create($package);

        $user_id = $response["data"]["user_detail"]["id"];
        $item = [
            "user_id" => $user_id,
            "receiptToken"=> fake()->text,
            "productId" => "1",
            "purchase_date" => Carbon::now()
        ];

        Payment::create($item);

        $user_package = [
            "user_id" => $user_id,
            "paymnet_id" => 1,
            "quota" => rand(1,100),
        ];

        UserPackage::create($user_package);
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('GET', 'api/user/packages/list')->assertStatus(200);

    }


    public function test_add_post5(){
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),
        ];
        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('GET', 'api/user/chats')->assertStatus(200);
    }


    public function test_add_post6(){
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),
        ];
        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];

        $package = [
            "name" => fake()->name,
            "fee" => rand(1,1000),
            "currency_unit" => "TL",
            "quota" => rand(1,100),
            "description" => fake()->text
        ];
        Package::create($package);

        $user_id = $response["data"]["user_detail"]["id"];
        $item = [
            "user_id" => $user_id,
            "receiptToken"=> fake()->text,
            "productId" => "1",
            "purchase_date" => Carbon::now()
        ];

        Payment::create($item);

        $user_package = [
            "user_id" => $user_id,
            "paymnet_id" => 1,
            "quota" => rand(1,100),
        ];

        UserPackage::create($user_package);

        $group = [
            "user_id" => 1,
            "user_package_id" => 1,
            "title" => fake()->text
        ];

        ChatGroup::create($group);
        $chat = [
            "chatId" => 1,
            "message" => fake()->text
        ];
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('POST', 'api/user/chats', $chat)->assertStatus(200);
    }

    public function test_add_post7(){
        $data = [
            'device_uuid' => fake()->name(),
            'device_name' => fake()->name(),
        ];
        $response = $this->json('POST', 'api/user/login', $data);
        $token = $response["data"]["token"];

        $package = [
            "name" => fake()->name,
            "fee" => rand(1,1000),
            "currency_unit" => "TL",
            "quota" => rand(1,100),
            "description" => fake()->text
        ];
        Package::create($package);

        $user_id = $response["data"]["user_detail"]["id"];
        $item = [
            "user_id" => $user_id,
            "receiptToken"=> fake()->text,
            "productId" => "1",
            "purchase_date" => Carbon::now()
        ];

        Payment::create($item);

        $user_package = [
            "user_id" => $user_id,
            "paymnet_id" => 1,
            "quota" => rand(1,100),
        ];

        UserPackage::create($user_package);

        $group = [
            "user_id" => 1,
            "user_package_id" => 1,
            "title" => fake()->text
        ];

        ChatGroup::create($group);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token])
            ->json('GET', 'api/user/chats/1')->assertStatus(200);
    }
}
