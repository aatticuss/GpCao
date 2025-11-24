<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    public function up(): void {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao');
            $table->string('icone')->nullable();
            $table->timestamps();
        });

    DB::table('technologies')->insert([
            [
                'nome' => 'PHP',
                'descricao' => 'Uma linguagem de script de uso geral, especialmente adequada para o desenvolvimento web. É amplamente utilizada em frameworks como Laravel.',
                'icone' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-plain.svg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'JavaScript',
                'descricao' => 'Uma linguagem de programação que é uma das principais tecnologias da World Wide Web, usada para criar interatividade e dinamismo em páginas web. É a base de frameworks front-end como React e Vue.',
                'icone' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-plain.svg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Python',
                'descricao' => 'Uma linguagem de programação de alto nível, interpretada e de propósito geral. Conhecida por sua sintaxe simples e legibilidade, é usada em desenvolvimento web, ciência de dados, inteligência artificial e automação.',
                'icone' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-plain.svg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Java',
                'descricao' => 'Uma linguagem de programação orientada a objetos, de propósito geral e baseada em classes. Famosa por seu slogan "Write Once, Run Anywhere", é usada em aplicativos empresariais, desenvolvimento mobile (Android) e sistemas de grande escala.',
                'icone' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-plain.svg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'C#',
                'descricao' => 'Uma linguagem de programação multi-paradigma, desenvolvida pela Microsoft como parte de sua iniciativa .NET. É amplamente utilizada em desenvolvimento de aplicativos Windows, jogos (Unity) e desenvolvimento web (ASP.NET).',
                'icone' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-plain.svg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('technologies');
    }
};
