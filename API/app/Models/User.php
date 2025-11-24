<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nome',
        'nome_usuario',
        'biografia',
        'email',
        'senha',
        'foto_perfil',
    ];
    
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->senha;
    }
    
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technology_user', 'usuario_id', 'tecnologia_id');
    }

    public function exercises()
    {
    return $this->hasMany(Exercise::class, 'usuario_id');
    }


    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    
}
