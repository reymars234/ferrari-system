<?php
// ════════════════════════════════════════════════════════════════
// CMD: php artisan make:migration add_coordinates_to_orders_table
// FILE: database/migrations/xxxx_add_coordinates_to_orders_table.php
// ════════════════════════════════════════════════════════════════

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('delivery_latitude', 10, 8)->nullable()->after('buyer_address');
            $table->decimal('delivery_longitude', 11, 8)->nullable()->after('delivery_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_latitude', 'delivery_longitude']);
        });
    }
};

// After creating the file run:
// php artisan migrate