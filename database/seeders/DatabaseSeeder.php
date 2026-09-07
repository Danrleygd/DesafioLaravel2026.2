<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       //admin

        $administrador =
            User::factory()
                ->create([

                    'nome' =>
                        'Administrador',

                    'email' =>
                        'admin@dtech.test',

                    'tipo' =>
                        'administrador',

                    'cpf' =>
                        '00000000001',
                ]);


        //usuario

        $usuarios =
            User::factory(
                5
            )
                ->create();


        //vendendor

        $vendedores =
            $usuarios
                ->push(
                    $administrador
                );


        //categorias

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


        $categorias =
            collect(
                $nomesCategorias
            )
                ->map(
                    function (
                        string $nome
                    ) {

                        return Categoria::factory()
                            ->create([
                                'nome' => $nome,
                            ]);
                    }
                );


        //produtos

        Produto::factory(
            24
        )
            ->create([

                'UsuarioId' =>
                    function () use (
                        $vendedores
                    ) {

                        return $vendedores
                            ->random()
                            ->id;
                    },

                'categoria_id' =>
                    function () use (
                        $categorias
                    ) {

                        return $categorias
                            ->random()
                            ->id;
                    },
            ]);


        //vendas

        $this->call([
            VendaSeeder::class,
        ]);
    }
}