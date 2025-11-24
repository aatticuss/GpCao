<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'conteudo_exercicio',
        'dificuldade',
        'usuario_id',
        'tecnologia_id',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'tecnologia_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'exercicio_id');
    }
}
