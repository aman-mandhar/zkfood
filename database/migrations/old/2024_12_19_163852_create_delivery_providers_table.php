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
        Schema::create('delivery_providers', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->enum('status', ['Active', 'Inactive'])->default('Active'); // Status
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('ref_id')->index()->default(1);
            $table->string('name')->nullable(); // 
            $table->string('address')->nullable(); // Address
            $table->unsignedBigInteger('city_id')->index();
            $table->string('mobile_no', 10)->nullable(); // Mobile Number
            $table->string('email')->nullable(); // Email
            $table->string('aadhar_no', 12)->nullable(); // Aadhar Number
            $table->string('pan_no', 10)->nullable(); // PAN Number
            $table->string('upi_id')->nullable(); // UPI ID
            $table->string('bank_name')->nullable(); // Bank Name
            $table->string('branch_name')->nullable(); // Branch Name
            $table->string('ifsc_code', 11)->nullable(); // IFSC Code
            $table->string('account_no', 18)->nullable(); // Account Number
            $table->string('account_holder_name')->nullable(); // Account Holder Name
            $table->string('account_type')->nullable(); // Account Type
            $table->string('aadhar_front')->nullable(); // Aadhar Front
            $table->string('aadhar_back')->nullable(); // Aadhar Back
            $table->string('pan_card')->nullable(); // PAN Card
            $table->string('cancel_cheque')->nullable(); // Cancel Cheque
            $table->string('photo')->nullable(); // Photo
            $table->timestamps(); // Adds created_at and updated_at
        
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ref_id')->references('id')->on('users');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_providers');
    }
};
