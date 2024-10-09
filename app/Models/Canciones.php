<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'artista', 'duracion', 'genero_id', 'imagen'
    ];

    public function genero()
    {
        return $this->belongsTo(Generos::class, 'genero_id');
    }
}