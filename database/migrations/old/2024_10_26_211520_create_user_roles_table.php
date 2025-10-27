<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert predefined roles
        DB::table('user_roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Customer', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Business Promoter', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Shop', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Food Vendor', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Service Provider', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Delivery Provider', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Transporter', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Teacher', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Student', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Media Partner', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Employee', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Super Admin', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Warehouse', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'Sub-Warehouse', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Store', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'name' => 'Vendor', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Merchant', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
