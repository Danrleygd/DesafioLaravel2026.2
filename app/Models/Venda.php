<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'Vendas';

    protected $fillable = [
        'CompradorId',
        'ValorTotal',
        'StatusPagamento',
        'LocalPagamento',
        'codigo_transacao',
    ];

    protected function casts(): array
    {
        return [
            'ValorTotal' => 'decimal:2',
        ];
    }

    public function comprador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'CompradorId');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'VendasId');
    }
}
