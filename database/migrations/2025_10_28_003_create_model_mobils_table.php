<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('model_mobils', function (Blueprint $table) {
            $table->id(); // id_mobil
            $table->string('brand', 100);
            $table->string('model', 150);
            $table->year('year')->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_mobils');
    }
};
