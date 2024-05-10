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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index();
            $table->unsignedBigInteger("productId")->index();
            $table->longText("receiptToken")->unique();
            $table->date("purchase_date");
            $table->enum("status", [1,0]);

            $table->foreign("user_id")->references("id")->on("users")->onDelete("restrict");
            $table->foreign("productId")->references("id")->on("packages")->onDelete("restrict");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
