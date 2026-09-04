<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCarrinho extends Model
{
    use HasFactory;

    protected $table = 'ItensCarrinho';

    protected $fillable = [
        'CarrinhoId',
        'ProdutoId',
        'quantidade',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
        ];
    }

    public function carrinho(): BelongsTo
    {
        return $this->belongsTo(Carrinho::class, 'CarrinhoId');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'ProdutoId');
    }
}
