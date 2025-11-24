<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('transaction_date')->useCurrent();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->enum('status', ['pending','paid','shipped','completed','cancelled'])->default('pending');
            $table->enum('payment_method', ['COD','Transfer','E-Wallet'])->nullable();
            $table->string('whatsapp_link', 255)->nullable();
            $table->string('location', 300)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
