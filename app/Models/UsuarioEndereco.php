<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioEndereco extends Pivot
{
    protected $table = 'Usuarios_Enderecos';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'UsuarioId',
        'EnderecoId',
    ];
}
