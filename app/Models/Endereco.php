<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Endereco extends Model
{
    protected $table =
        'Enderecos';


    protected $fillable = [
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'Usuarios_Enderecos',
            'EnderecoId',
            'UsuarioId'
        );
    }
}