<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Refund tracking
            $table->string('refund_status')->nullable()->after('payment_reference');
            // Values: null | 'pending' | 'processed' | 'failed'

            $table->string('refund_reference')->nullable()->after('refund_status');
            $table->timestamp('refunded_at')->nullable()->after('refund_reference');
            $table->text('cancel_reason')->nullable()->after('refunded_at');

            // COD payment confirmation by driver
            $table->boolean('cod_paid')->default(false)->after('cancel_reason');
            $table->timestamp('cod_paid_at')->nullable()->after('cod_paid');
            $table->unsignedBigInteger('cod_confirmed_by')->nullable()->after('cod_paid_at');
            // cod_confirmed_by = driver user_id
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refund_reference',
                'refunded_at',
                'cancel_reason',
                'cod_paid',
                'cod_paid_at',
                'cod_confirmed_by',
            ]);
        });
    }
};

