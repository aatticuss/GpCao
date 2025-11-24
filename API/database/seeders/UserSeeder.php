<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            [
                'nome' => 'Artur Silva',
                'nome_usuario' => 'arturs',
                'biografia' => 'Desenvolvedor apaixonado por Laravel e PHP.',
                'email' => 'artur@example.com',
                'senha' => Hash::make('senha123'),
            ],
            [
                'nome' => 'Maria Oliveira',
                'nome_usuario' => 'mariao',
                'biografia' => 'Estudante de programação e tecnologia.',
                'email' => 'maria@example.com',
                'senha' => Hash::make('senha123'),
            ],
            [
                'nome' => 'João Santos',
                'nome_usuario' => 'joaos',
                'biografia' => 'Front-end developer e designer.',
                'email' => 'joao@example.com',
                'senha' => Hash::make('senha123'),
            ],
            [
                'nome' => 'Fernanda Costa',
                'nome_usuario' => 'fernandac',
                'biografia' => 'Engenheira de software full stack.',
                'email' => 'fernanda@example.com',
                'senha' => Hash::make('senha123'),
            ],
            [
                'nome' => 'Lucas Pereira',
                'nome_usuario' => 'lucasp',
                'biografia' => 'Entusiasta de tecnologia e inteligência artificial.',
                'email' => 'lucas@example.com',
                'senha' => Hash::make('senha123'),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
