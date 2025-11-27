<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_id')->constrained('keranjangs')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->constrained('spareparts')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->nullable(); // optional: snapshot price
            $table->timestamps();
            $table->unique(['keranjang_id','sparepart_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_cards');
    }
};
