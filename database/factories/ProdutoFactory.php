<?php

namespace Database\Factories;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => ucfirst($this->faker->words(3, true)),
            'descricao' => $this->faker->paragraph(),
            'preco' => $this->faker->randomFloat(2, 50, 2500),
            'quantidade' => $this->faker->numberBetween(1, 50),
            'foto' => 'https://picsum.photos/640/480?random=' . $this->faker->numberBetween(1, 1000),
            'categoria_id' => Categoria::factory(),
            'UsuarioId' => User::factory(),
        ];
    }
}