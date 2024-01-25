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
        Schema::table("products", function (Blueprint $table) {
            $table->enum("measurement_unit",["gm","ml","l","kg","mg","gal","oz"], "floz", "lb")->after('shipping_returns')->default("ml");
            $table->double("measurement_value")->after('measurement_unit')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn("measurement_unit");
            $table->dropColumn("measurement_value");
        });
    }
};