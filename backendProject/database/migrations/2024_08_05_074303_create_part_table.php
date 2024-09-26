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
        Schema::create('parts', function (Blueprint $table) {
            $table->bigIncrements('Part_ID');
            $table->unsignedBigInteger('Category_ID');
            $table->string('Name', 255)->notNullable();
            $table->string('Description', 255)->notNullable();
            $table->integer('Quantity')->default(0)->notNullable();
            $table->decimal('PricePerUnit', 10,2)->notNullable();
            $table->timestamps();

            $table->foreign('Category_ID')->references('Category_ID')->on('category_part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
