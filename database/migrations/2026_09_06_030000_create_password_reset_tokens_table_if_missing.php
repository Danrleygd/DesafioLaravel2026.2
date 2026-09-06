<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | O BREEZE PRECISA DESTA TABELA PARA OS TOKENS
        |--------------------------------------------------------------------------
        |
        | A migration só cria a tabela se ela ainda não existir,
        | evitando conflito com a migration padrão do Laravel.
        |
        */

        if (
            !Schema::hasTable(
                'password_reset_tokens'
            )
        ) {
            Schema::create(
                'password_reset_tokens',
                function (Blueprint $table) {

                    $table
                        ->string('email')
                        ->primary();

                    $table
                        ->string('token');

                    $table
                        ->timestamp(
                            'created_at'
                        )
                        ->nullable();
                }
            );
        }
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | NÃO REMOVE AUTOMATICAMENTE
        |--------------------------------------------------------------------------
        |
        | A tabela pode ter sido criada pela migration original do Breeze.
        |
        */
    }
};
