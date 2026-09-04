<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'Produtos';

    protected $fillable = [
        'nome',
        'descricao',
        'foto',
        'preco',
        'quantidade',
        'UsuarioId',
        'categoria_id',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'quantidade' => 'integer',
        ];
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId', 'id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(ProdutoFoto::class, 'ProdutoId', 'id');
    }

    public function carrinhos(): BelongsToMany
    {
        return $this->belongsToMany(
            Carrinho::class,
            'ItensCarrinho',
            'ProdutoId',
            'CarrinhoId'
        )
        ->withPivot('quantidade')
        ->withTimestamps();
    }

    public function vendas(): BelongsToMany
    {
        return $this->belongsToMany(
            Venda::class,
            'ItensVendas',
            'ProdutoId',
            'VendasId'
        )
        ->withPivot([
            'VendedorId',
            'quantidade',
            'ValorUnitario',
            'subtotal'
        ])
        ->withTimestamps();
    }
}