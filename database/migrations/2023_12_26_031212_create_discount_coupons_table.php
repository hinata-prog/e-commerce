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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //discount coupon code
            $table->string('code');
            //human readable discount coupon code name
            $table->string('name')->nullable();
            //description of coupon
            $table->string('description',600)->nullable();
            //max no of uses the coupon has
            $table->integer('max_uses')->nullable();
            //max no of times a user can use this coupon
            $table->integer('max_uses_user')->nullable();
            //wheteher coupon is percentage or fixed price
            $table->enum('type', ['percent','fixed'])->default('fixed');
            //discount amount based on type
            $table->double('discount_amount',10,2);
            //amount to discount based on type
            $table->double('min_amount',10,2);
            $table->integer('status')->default(1);
            //when the coupon begins
            $table->timestamp('starts_at')->nullable();
            //when the coupon expires
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
