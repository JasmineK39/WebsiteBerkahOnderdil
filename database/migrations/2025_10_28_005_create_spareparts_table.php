<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            // relasi ke model_mobils jika sparepart terkait mobil tertentu
            $table->foreignId('model_mobil_id')->nullable()->constrained('model_mobils')->nullOnDelete();
            $table->string('name', 150);
            $table->string('brand', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->enum('grade', ['A','B','C'])->default('A');
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->enum('status', ['available','sold_out'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
