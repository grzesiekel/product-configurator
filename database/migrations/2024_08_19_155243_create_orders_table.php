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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed','closed', 'declined'])->default('pending');
            $table->decimal('shipping_price', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('deposit_amount')->nullable();
            $table->string('subiekt_zk')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
