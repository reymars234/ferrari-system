<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('license_number', 50)->nullable()->after('address');
            $table->string('vehicle_info')->nullable()->after('license_number');
            $table->enum('driver_status', ['available','busy','offline'])->default('available')->after('vehicle_info');
            $table->boolean('is_active')->default(true)->after('driver_status');
        });
 
        // Modify role enum to include 'driver'
        // For MySQL, you need to use raw statement:
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','driver','admin') DEFAULT 'user'");
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['license_number','vehicle_info','driver_status','is_active']);
        });
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user','admin') DEFAULT 'user'");
    }
};