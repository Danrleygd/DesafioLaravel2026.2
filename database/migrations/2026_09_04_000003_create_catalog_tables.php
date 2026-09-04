<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->timestamps();
        });

        Schema::create('Produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('preco', 10, 2);
            $table->unsignedInteger('quantidade')->default(0);
            $table->foreignId('UsuarioId')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('Categorias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Produtos');
        Schema::dropIfExists('Categorias');
    }
};
