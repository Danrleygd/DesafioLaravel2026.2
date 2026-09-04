<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrinho extends Model
{
    use HasFactory;

    protected $table = 'Carrinhos';

    protected $fillable = [
        'UsuarioId',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemCarrinho::class, 'CarrinhoId');
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'ItensCarrinho', 'CarrinhoId', 'ProdutoId')
            ->withPivot('quantidade')
            ->withTimestamps();
    }
}
