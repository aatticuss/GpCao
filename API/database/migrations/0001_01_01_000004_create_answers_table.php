<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('texto_resposta')->nullable();
            $table->tinyInteger('nota')->nullable();
            $table->text('avaliacao')->nullable();

            $table->foreignId('usuario_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('exercicio_id')
            ->constrained('exercises')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('answers');
    }
};
