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
        Schema::create('repair_processes', function (Blueprint $table) {
            $table->bigIncrements('Process_ID');
            $table->unsignedBigInteger('Quotation_ID');
            // $table->unsignedBigInteger('partusage_ID');
            $table->unsignedBigInteger('Step_ID');
            // $table->unsignedBigInteger('User_ID');
            $table->string('licenseplate', 20)->notNullable();
            $table->text('Description')->notNullable();
            $table->string('Status', 50)->notNullable();
            $table->timestamps();

            $table->foreign('Quotation_ID')->references('Quotation_ID')->on('quotations');
            // $table->foreign('partusage_ID')->references('partusage_ID')->on('part_usage');
            $table->foreign('Step_ID')->references('Step_ID')->on('repair_steps');
            // $table->foreign('User_ID')->references('User_ID')->on('puser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_process');
    }
};
