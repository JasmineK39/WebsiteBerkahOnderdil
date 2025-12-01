<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // biar support FK
            $table->id();

            // Relasi ke transaksi
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();

            // Relasi ke sparepart (nullable karena nullOnDelete)
            $table->foreignId('sparepart_id')
                ->nullable() // ✅ WAJIB
                ->constrained('spareparts')
                ->nullOnDelete();

            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->storedAs('quantity * price')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
