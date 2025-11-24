<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    public function up(): void {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('dificuldade');
            $table->text('conteudo_exercicio')->nullable();
            
            $table->foreignId('usuario_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('tecnologia_id')
            ->constrained('technologies')
            ->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('exercises')->insert([
            [
                'usuario_id' => 1, // Mydei
                'tecnologia_id' => 1, // PHP
                'titulo' => 'Exercício de PHP para iniciantes',
                'descricao' => 'Exercício de PHP para iniciantes',
                'dificuldade' => 'Fácil',
                'conteudo_exercicio' => 'Crie uma função que retorne a soma de dois números.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'usuario_id' => 2, // Acheron
                'tecnologia_id' => 2, // JavaScript
                'titulo' => 'Exercício de JavaScript para iniciantes',
                'descricao' => 'Exercício de JavaScript para iniciantes',
                'dificuldade' => 'Fácil',
                'conteudo_exercicio' => 'Escreva uma função que inverta uma string.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('exercises');
    }
};

