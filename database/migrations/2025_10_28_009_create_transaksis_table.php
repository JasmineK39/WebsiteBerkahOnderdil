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
<<<<<<< HEAD
            $table->enum('payment_method', ['Cash','Transfer'])->nullable();
=======
            $table->enum('payment_method', ['COD','Transfer','E-Wallet'])->nullable();
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
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
