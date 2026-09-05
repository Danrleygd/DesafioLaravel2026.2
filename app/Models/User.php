<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'Usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
        'cpf',
        'data_nascimento',
        'telefone',
        'saldo',
        'foto',
        'criador_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'senha',
        'remember_token',
    ];

    protected function name(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value, array $attributes) => $attributes['nome'] ?? null,
            set: fn ($value) => ['nome' => $value],
        );
    }

    protected function password(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value, array $attributes) => $attributes['senha'] ?? null,
            set: fn ($value) => ['senha' => $value],
        );
    }

    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->senha;
    }

    public function setRememberToken($value): void
    {
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(self::class, 'criador_id');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(self::class, 'criador_id');
    }

    public function enderecos()
{
    return $this->belongsToMany(
        Endereco::class,
        'Usuarios_Enderecos',
        'UsuarioId',
        'EnderecoId'
    );
}

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'UsuarioId');
    }

    public function carrinho(): HasOne
    {
        return $this->hasOne(Carrinho::class, 'UsuarioId');
    }

    public function cartoes(): HasMany
    {
        return $this->hasMany(Cartao::class, 'UsuarioId');
    }

    public function vendasComoComprador(): HasMany
    {
        return $this->hasMany(Venda::class, 'CompradorId');
    }

    public function itensVendidos(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'VendedorId');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
            'email_verified_at' => 'datetime',
            'data_nascimento' => 'date',
            'saldo' => 'decimal:2',
        ];
    }
}
