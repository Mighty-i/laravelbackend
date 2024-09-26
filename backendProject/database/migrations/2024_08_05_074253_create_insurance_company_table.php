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
        Schema::create('insurance_company', function (Blueprint $table) {
            $table->id('Company_ID');
            $table->string('Name', 255)->notNullable();
            $table->string('Address', 255)->notNullable();
            $table->string('PhoneNumber', 100)->notNullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_company');
    }
};
