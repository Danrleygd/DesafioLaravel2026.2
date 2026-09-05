<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABELA
    |--------------------------------------------------------------------------
    */

    protected $table =
        'Produtos';


    /*
    |--------------------------------------------------------------------------
    | CAMPOS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'nome',
        'descricao',
        'foto',
        'preco',
        'quantidade',
        'UsuarioId',
        'categoria_id',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'preco' =>
            'decimal:2',

        'quantidade' =>
            'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | VENDEDOR
    |--------------------------------------------------------------------------
    */

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'UsuarioId'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORIA
    |--------------------------------------------------------------------------
    */

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(
            Categoria::class,
            'categoria_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FOTOS
    |--------------------------------------------------------------------------
    */

    public function fotos(): HasMany
    {
        return $this->hasMany(
            ProdutoFoto::class,
            'ProdutoId'
        );
    }
}