<?php

namespace App\Models;

use Database\Factories\VendaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'Vendas';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'ValorTotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory(): VendaFactory
    {
        return VendaFactory::new();
    }

    public function comprador()
    {
        return $this->belongsTo(
            User::class,
            'CompradorId',
            'id'
        );
    }
}