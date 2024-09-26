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
        Schema::create('part_usage', function (Blueprint $table) {
            $table->bigIncrements('partusage_ID');
            $table->unsignedBigInteger('Process_ID');
            $table->unsignedBigInteger('Part_ID');
            $table->integer('Quantity')->default(0)->notNullable();
            $table->timestamps();

            $table->foreign('Part_ID')->references('Part_ID')->on('parts');
            $table->foreign('Process_ID')->references('Process_ID')->on('repair_processes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_usage');
    }
};
