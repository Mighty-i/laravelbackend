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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('Vehicle_ID');
            $table->unsignedBigInteger('Brand_ID');
            $table->string('Model', 50)->notNullable();
            $table->string('Year', 4)->notNullable();
            $table->timestamps();

            $table->foreign('Brand_ID')->references('Brand_ID')->on('car_brands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
