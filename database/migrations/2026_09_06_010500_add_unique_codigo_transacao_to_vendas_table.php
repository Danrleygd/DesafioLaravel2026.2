<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | VERIFICA SE O ÍNDICE JÁ EXISTE
        |--------------------------------------------------------------------------
        */

        $indiceExiste = DB::table(
            'information_schema.statistics'
        )
            ->where(
                'table_schema',
                DB::getDatabaseName()
            )
            ->where(
                'table_name',
                'Vendas'
            )
            ->where(
                'index_name',
                'vendas_codigo_transacao_unique'
            )
            ->exists();


        /*
        |--------------------------------------------------------------------------
        | CRIA SOMENTE SE AINDA NÃO EXISTIR
        |--------------------------------------------------------------------------
        */

        if (!$indiceExiste) {

            Schema::table(
                'Vendas',
                function (Blueprint $table) {

                    $table
                        ->unique(
                            'codigo_transacao',
                            'vendas_codigo_transacao_unique'
                        );
                }
            );
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | VERIFICA SE O ÍNDICE EXISTE
        |--------------------------------------------------------------------------
        */

        $indiceExiste = DB::table(
            'information_schema.statistics'
        )
            ->where(
                'table_schema',
                DB::getDatabaseName()
            )
            ->where(
                'table_name',
                'Vendas'
            )
            ->where(
                'index_name',
                'vendas_codigo_transacao_unique'
            )
            ->exists();


        /*
        |--------------------------------------------------------------------------
        | REMOVE SOMENTE SE EXISTIR
        |--------------------------------------------------------------------------
        */

        if ($indiceExiste) {

            Schema::table(
                'Vendas',
                function (Blueprint $table) {

                    $table->dropUnique(
                        'vendas_codigo_transacao_unique'
                    );
                }
            );
        }
    }
};