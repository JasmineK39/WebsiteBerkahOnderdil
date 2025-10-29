<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->constrained('spareparts')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->tinyInteger('rating')->unsigned()->default(5); // 1..5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id','sparepart_id','transaksi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
