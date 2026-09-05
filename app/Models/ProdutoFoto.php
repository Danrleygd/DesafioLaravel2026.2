<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdutoFoto extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABELA
    |--------------------------------------------------------------------------
    */

    protected $table =
        'Produtos_Fotos';


    /*
    |--------------------------------------------------------------------------
    | CAMPOS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'ProdutoId',
        'foto',
        'principal',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'principal' =>
            'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | PRODUTO
    |--------------------------------------------------------------------------
    */

    public function produto(): BelongsTo
    {
        return $this->belongsTo(
            Produto::class,
            'ProdutoId'
        );
    }
}