<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'car_id')) {
                $table->unsignedBigInteger('car_id')->after('user_id');
                $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            }
            if (!Schema::hasColumn('cart_items', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('car_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'car_id')) {
                $table->dropForeign(['car_id']);
                $table->dropColumn('car_id');
            }
            if (Schema::hasColumn('cart_items', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};