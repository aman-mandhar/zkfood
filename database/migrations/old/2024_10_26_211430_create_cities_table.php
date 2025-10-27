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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->timestamps();
        });

        // Insert predefined cities
        DB::table('cities')->insert([
            ['id' => 1, 'name' => 'Delhi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Mumbai', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Kolkata', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Chennai', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Chandigarh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Amritsar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Ludhiana', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Jalandhar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Patiala', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Bathinda', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Mohali', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Pathankot', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Hoshiarpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Moga', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'Malerkotla', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Abohar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'name' => 'Khanna', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Barnala', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'name' => 'Fazilka', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'name' => 'Firozpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'name' => 'Faridkot', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'name' => 'Phagwara', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'name' => 'Kapurthala', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'name' => 'Mansa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'name' => 'Muktsar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'name' => 'Rupnagar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'name' => 'Sangrur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'name' => 'Tarn Taran', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'name' => 'Banga', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'name' => 'Rajpura', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'name' => 'Sultanpur Lodhi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'name' => 'Zirakpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'name' => 'Mandi Gobindgarh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'name' => 'Bangalore', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'name' => 'Hyderabad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'name' => 'Ahmedabad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'name' => 'Pune', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'name' => 'Surat', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'name' => 'Jaipur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'name' => 'Lucknow', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'name' => 'Kanpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'name' => 'Nagpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'name' => 'Indore', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'name' => 'Thane', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'name' => 'Bhopal', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 46, 'name' => 'Visakhapatnam', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'name' => 'Patna', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'name' => 'Vadodara', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'name' => 'Ghaziabad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'name' => 'Agra', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 51, 'name' => 'Nashik', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'name' => 'Pimpri-Chinchwad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 53, 'name' => 'Kalyan-Dombivli', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'name' => 'Vasai-Virar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 55, 'name' => 'Aurangabad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 56, 'name' => 'Rajkot', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 57, 'name' => 'Meerut', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 58, 'name' => 'Jabalpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 59, 'name' => 'Guntur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 60, 'name' => 'Coimbatore', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 61, 'name' => 'Mysore', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 62, 'name' => 'Ranchi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 63, 'name' => 'Hubli-Dharwad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 64, 'name' => 'Guwahati', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 65, 'name' => 'Chandrapur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 66, 'name' => 'Bikaner', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 67, 'name' => 'Udaipur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 68, 'name' => 'Allahabad', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 69, 'name' => 'Saharanpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 70, 'name' => 'Gorakhpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 71, 'name' => 'Bareilly', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 72, 'name' => 'Tiruchirappalli', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 73, 'name' => 'Salem', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 74, 'name' => 'Malappuram', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 75, 'name' => 'Warangal', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 76, 'name' => 'Bhavnagar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 77, 'name' => 'Jodhpur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 78, 'name' => 'Bhilai', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 79, 'name' => 'Amravati', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

