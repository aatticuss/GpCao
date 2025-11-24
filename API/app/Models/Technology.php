<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'icone', 'descricao'];

    
    public function users()
    {
        return $this->belongsToMany(User::class, 'technology_user', 'tecnologia_id', 'usuario_id');
    }

    
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
