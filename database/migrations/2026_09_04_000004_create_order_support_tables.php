<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('cep', 8);
            $table->string('logradouro');
            $table->string('numero', 20);
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado', 2);
            $table->timestamps();
        });

        Schema::create('Usuarios_Enderecos', function (Blueprint $table) {
            $table->foreignId('UsuarioId')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('EnderecoId')->constrained('Enderecos')->cascadeOnDelete();
            $table->primary(['UsuarioId', 'EnderecoId']);
        });

        Schema::create('Carrinhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UsuarioId')->unique()->constrained('usuarios')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ItensCarrinho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CarrinhoId')->constrained('Carrinhos')->cascadeOnDelete();
            $table->foreignId('ProdutoId')->constrained('Produtos')->cascadeOnDelete();
            $table->unsignedInteger('quantidade')->default(1);
            $table->timestamps();
            $table->unique(['CarrinhoId', 'ProdutoId']);
        });

        Schema::create('Cartoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UsuarioId')->constrained('usuarios')->cascadeOnDelete();
            $table->string('token')->unique();
            $table->string('bandeira', 30);
            $table->string('ultimos_digitos', 4);
            $table->unsignedTinyInteger('mes_expiracao');
            $table->unsignedSmallInteger('ano_expiracao');
            $table->boolean('principal')->default(false);
            $table->timestamps();
        });

        Schema::create('Vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CompradorId')->constrained('usuarios')->cascadeOnDelete();
            $table->decimal('ValorTotal', 10, 2)->default(0);
            $table->string('StatusPagamento', 30)->default('pendente');
            $table->string('LocalPagamento', 100)->nullable();
            $table->string('codigo_transacao')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('ItensVendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('VendasId')->constrained('Vendas')->cascadeOnDelete();
            $table->foreignId('ProdutoId')->constrained('Produtos')->restrictOnDelete();
            $table->foreignId('VendedorId')->constrained('usuarios')->restrictOnDelete();
            $table->unsignedInteger('quantidade');
            $table->decimal('ValorUnitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('Produtos_Fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ProdutoId')->constrained('Produtos')->cascadeOnDelete();
            $table->string('foto');
            $table->boolean('principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Produtos_Fotos');
        Schema::dropIfExists('ItensVendas');
        Schema::dropIfExists('Vendas');
        Schema::dropIfExists('Cartoes');
        Schema::dropIfExists('ItensCarrinho');
        Schema::dropIfExists('Carrinhos');
        Schema::dropIfExists('Usuarios_Enderecos');
        Schema::dropIfExists('Enderecos');
    }
};
