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
        Schema::create('pusers', function (Blueprint $table) {
            $table->id('User_ID');
            $table->string('username', 255);
            $table->string('password');
            $table->string('email', 255);
            $table->rememberToken();
            $table->string('name', 255);
            $table->unsignedBigInteger('Role_ID');
            $table->timestamps();

            $table->foreign('Role_ID')->references('Role_ID')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puser');
    }
};
