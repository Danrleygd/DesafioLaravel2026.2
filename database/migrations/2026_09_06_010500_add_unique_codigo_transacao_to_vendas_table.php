<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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


    public function down(): void
    {
        Schema::table(
            'Vendas',
            function (Blueprint $table) {
                $table
                    ->dropUnique(
                        'vendas_codigo_transacao_unique'
                    );
            }
        );
    }
};
