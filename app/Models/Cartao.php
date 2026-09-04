<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cartao extends Model
{
    use HasFactory;

    protected $table = 'Cartoes';

    protected $fillable = [
        'UsuarioId',
        'token',
        'bandeira',
        'ultimos_digitos',
        'mes_expiracao',
        'ano_expiracao',
        'principal',
    ];

    protected function casts(): array
    {
        return [
            'mes_expiracao' => 'integer',
            'ano_expiracao' => 'integer',
            'principal' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId');
    }
}
