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
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('Quotation_ID');
            $table->unsignedBigInteger('Customer_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Company_ID');
            $table->unsignedBigInteger('Vehicle_ID');
            $table->timestamp('QuotationDate')->useCurrent();
            $table->decimal('TotalAmount', 10, 2);
            $table->string('Status', 20);
            $table->string('color', 20);
            $table->string('licenseplate', 20);
            $table->string('damageassessment', 20);
            $table->text('problemdetails');
            $table->dateTime('admissionDate');
            $table->string('PaymentMethod', 50)->nullable();
            $table->timestamp('PaymentDate')->nullable()->useCurrent();
            $table->string('ImageSlie', 255)->nullable();
            
            $table->foreign('Customer_ID')->references('Customer_ID')->on('customers');
            $table->foreign('User_ID')->references('User_ID')->on('pusers');
            $table->foreign('Company_ID')->references('Company_ID')->on('insurance_company');
            $table->foreign('Vehicle_ID')->references('Vehicle_ID')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
