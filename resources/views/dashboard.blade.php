<x-app-layout>
    <div class="dtech-body">

        <!-- Conteúdo Principal -->
        <main class="dtech-main">
            <!-- Header do Painel -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800">Painel de Controle</h1>
                    <p class="text-gray-500 text-sm">Gerencie seus produtos, vendas e catálogo da loja D-tech.</p>
                </div>
                <button class="dtech-btn-purple">
                    + Cadastrar Produto
                </button>
            </div>

            <!-- Cards de Métricas Estilizados em Roxo -->
            <div class="dtech-card-grid">
                <div class="dtech-stat-card">
                    <span class="dtech-stat-title">Vendas Hoje</span>
                    <span class="dtech-stat-value">R$ 3.850,00</span>
                    <span class="dtech-stat-badge">+12% vs ontem</span>
                </div>
                <div class="dtech-stat-card">
                    <span class="dtech-stat-title">Pedidos Pendentes</span>
                    <span class="dtech-stat-value">24</span>
                    <span class="dtech-stat-badge">Aguardando envio</span>
                </div>
                <div class="dtech-stat-card">
                    <span class="dtech-stat-title">Produtos no Catálogo</span>
                    <span class="dtech-stat-value">142</span>
                    <span class="dtech-stat-badge">8 Categorias</span>
                </div>
                <div class="dtech-stat-card">
                    <span class="dtech-stat-title">Avaliação Média</span>
                    <span class="dtech-stat-value">4.9 ★</span>
                    <span class="dtech-stat-badge">320 avaliações</span>
                </div>
            </div>

            <!-- Tabela de Produtos / Vendas Recentes -->
            <div class="dtech-panel">
                <div class="dtech-panel-title">
                    <span>Últimas Vendas</span>
                    <a href="#" class="text-xs text-[#7C3AED] hover:underline">Ver tudo</a>
                </div>

                <table class="dtech-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold text-gray-800">Razer Kraken THY V2 - Edição Gengar</td>
                            <td>Áudio</td>
                            <td>R$ 899,00</td>
                            <td><span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded-md">Concluído</span></td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-800">Fone de Ouvido D-tech Wireless</td>
                            <td>Acessórios</td>
                            <td>R$ 50,00</td>
                            <td><span class="text-purple-600 font-semibold bg-purple-50 px-2 py-1 rounded-md">Em separação</span></td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-800">Cheeky Controller by Deadpool</td>
                            <td>Controles</td>
                            <td>R$ 350,00</td>
                            <td><span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded-md">Concluído</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-app-layout>