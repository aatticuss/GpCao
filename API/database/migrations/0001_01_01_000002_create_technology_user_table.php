<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('technology_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('tecnologia_id')
            ->constrained('technologies')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('technology_user');
    }
};
