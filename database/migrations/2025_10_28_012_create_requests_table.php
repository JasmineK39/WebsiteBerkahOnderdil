<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('brand_req', 100)->nullable();
            $table->string('model_req', 150)->nullable();
            $table->year('year_req')->nullable();
            $table->string('sparepart_req', 150)->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending','in_progress','fulfilled','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
