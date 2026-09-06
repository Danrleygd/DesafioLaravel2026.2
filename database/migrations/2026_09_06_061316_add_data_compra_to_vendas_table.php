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
        | ADICIONA DATA DA COMPRA
        |--------------------------------------------------------------------------
        */

        if (
            !Schema::hasColumn(
                'Vendas',
                'data_compra'
            )
        ) {
            Schema::table(
                'Vendas',
                function (Blueprint $table) {

                    $table
                        ->dateTime('data_compra')
                        ->nullable()
                        ->after('codigo_transacao');
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CORRIGE VENDAS ANTIGAS
        |--------------------------------------------------------------------------
        |
        | Como já existem registros no banco,
        | eles precisam receber uma data.
        |
        */

        DB::table('Vendas')
            ->whereNull('data_compra')
            ->update([
                'data_compra' => now(),
            ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasColumn(
                'Vendas',
                'data_compra'
            )
        ) {
            Schema::table(
                'Vendas',
                function (Blueprint $table) {

                    $table->dropColumn(
                        'data_compra'
                    );
                }
            );
        }
    }
};