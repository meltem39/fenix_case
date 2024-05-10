<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index();
            $table->unsignedBigInteger("user_package_id")->index();
            $table->string("title");

            $table->foreign("user_id")->references("id")->on("users")->onDelete("restrict");
            $table->foreign("user_package_id")->references("id")->on("user_packages")->onDelete("restrict");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_groups');
    }
};
