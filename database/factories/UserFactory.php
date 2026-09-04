<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'senha' => static::$password ??= Hash::make('password'),
            'tipo' => 'usuario',
            'cpf' => fake()->unique()->numerify('###########'),
            'data_nascimento' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'telefone' => fake()->numerify('###########'),
            'saldo' => 0,
            'foto' => null,
            'criador_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => fake()->unique()->safeEmail(),
        ]);
    }
}
