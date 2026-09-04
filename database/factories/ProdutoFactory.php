<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        $preco = fake()->randomFloat(2, 10, 5000);

        return [
            'nome' => fake()->words(3, true),
            'descricao' => fake()->paragraph(),
            'foto' => null,
            'preco' => $preco,
            'quantidade' => fake()->numberBetween(0, 100),
            'UsuarioId' => User::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
}