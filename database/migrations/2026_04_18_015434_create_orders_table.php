<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_address');
            $table->string('buyer_contact', 20);
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'processing', 'delivered', 'cancelled'])->default('pending');
            $table->text('delivery_notes')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};