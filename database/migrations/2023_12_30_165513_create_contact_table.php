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
        Schema::create('contact', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('corporate_office')->default('Itahari, Sunsari');
            $table->string('email')->default('lookmenepal@gmail.com');
            $table->string('phone_number')->default('025586765');
            $table->string('mobile_number')->default('9804094094');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};