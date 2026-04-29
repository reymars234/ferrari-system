<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'driver_id')) {
                $table->unsignedBigInteger('driver_id')->nullable()->after('status');
                $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->default('cod')->after('status');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'admin_accepted')) {
                $table->tinyInteger('admin_accepted')->default(0)->after('payment_reference');
            }
            if (!Schema::hasColumn('orders', 'admin_accepted_at')) {
                $table->timestamp('admin_accepted_at')->nullable()->after('admin_accepted');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['driver_id','payment_method','payment_status','payment_reference','admin_accepted','admin_accepted_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    if ($col === 'driver_id') $table->dropForeign(['driver_id']);
                    $table->dropColumn($col);
                }
            }
        });
    }
};