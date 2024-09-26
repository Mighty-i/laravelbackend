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
        Schema::create('repair_status', function (Blueprint $table) {
            $table->bigIncrements('Status_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Process_ID');
            $table->string('StatusType', 50);
            $table->string('Image1')->nullable();
            $table->string('Image2')->nullable();
            $table->string('Image3')->nullable();
            $table->string('Status', 50);
            $table->timestamps();

            $table->foreign('Process_ID')->references('Process_ID')->on('repair_processes');
            $table->foreign('User_ID')->references('User_ID')->on('pusers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_status');
    }
};
