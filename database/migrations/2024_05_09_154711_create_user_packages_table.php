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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index();
            $table->unsignedBigInteger("payment_id")->index()->nullable();
            $table->integer("quota");
            $table->date("quota_completion_date")->nullable();

            $table->foreign("user_id")->references("id")->on("users")->onDelete("restrict");
            $table->foreign("payment_id")->references("id")->on("payments")->onDelete("restrict");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};
