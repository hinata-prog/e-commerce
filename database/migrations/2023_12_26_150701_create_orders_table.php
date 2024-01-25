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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal',10,2);
            $table->double('shipping',10.2);
            $table->foreignUuid('discount_coupon_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('coupon_code')->nullable();
            $table->double('discount',10.2)->nullable();
            $table->double('grand_total',10,2);

            //User shipping address
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->string('municipality');
            $table->string('city');
            $table->string('house_no');
            $table->text('notes')->nullable();
            $table->timestamp('shipped_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
