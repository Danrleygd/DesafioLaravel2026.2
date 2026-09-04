<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $administrador = User::factory()->create([
            'nome' => 'Administrador',
            'email' => 'admin@dtech.test',
            'tipo' => 'administrador',
            'cpf' => '00000000001',
        ]);

        $usuarios = User::factory(5)->create();
        $vendedores = $usuarios->push($administrador);

        $nomesCategorias = [
            'Smartphones',
            'Tablets',
            'Computadores',
            'Controles',
            'Consoles',
            'Audio',
            'Acessorios',
            'Eletrodomesticos',
        ];

        $categorias = collect($nomesCategorias)->map(
            fn (string $nome) => Categoria::factory()->create(['nome' => $nome])
        );

        Produto::factory(24)->create([
            'UsuarioId' => fn () => $vendedores->random()->id,
            'categoria_id' => fn () => $categorias->random()->id,
        ]);
    }
}
