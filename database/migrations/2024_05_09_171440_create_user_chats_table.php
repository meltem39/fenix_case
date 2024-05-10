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
        Schema::create('user_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chat_group_id")->index();
            $table->text("message");
            $table->text("answer");

            $table->foreign("chat_group_id")->references("id")->on("chat_groups")->onDelete("restrict");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_chats');
    }
};
