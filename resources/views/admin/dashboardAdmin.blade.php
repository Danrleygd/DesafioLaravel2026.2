@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
    @vite([
        'resources/css/dashboardAdmin.css'
    ])
@endpush

@section('content')

    <div class="dashboard-admin">

        {{-- =====================================================
            CABEÇALHO
        ====================================================== --}}

        <header class="dashboard-header">

            <div>
                <h1>
                    Dashboard
                </h1>

                <p>
                    Visão geral da administração da D-tech.
                </p>
            </div>

            <div class="dashboard-user">

                <div class="dashboard-user-info">
                    <strong>
                        {{ Auth::user()->nome ?? 'Administrador' }}
                    </strong>

                    <span>
                        Administrador
                    </span>
                </div>

                <div class="dashboard-user-avatar">

                    @if(Auth::user()?->foto)

                        @php
                            $foto = Auth::user()->foto;

                            if (
                                str_starts_with($foto, 'http://') ||
                                str_starts_with($foto, 'https://')
                            ) {
                                $fotoUsuario = $foto;
                            } else {
                                $fotoUsuario = asset(
                                    'storage/' . ltrim($foto, '/')
                                );
                            }
                        @endphp

                        <img
                            src="{{ $fotoUsuario }}"
                            alt="{{ Auth::user()->nome }}"
                        >

                    @else

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                            ></circle>

                            <path
                                d="M4 21a8 8 0 0 1 16 0"
                            ></path>
                        </svg>

                    @endif

                </div>

            </div>

        </header>


        {{-- =====================================================
            CARDS RESUMO
        ====================================================== --}}

        <section class="dashboard-cards">

            {{-- USUÁRIOS --}}
            <article class="dashboard-card">

                <div class="dashboard-card-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                        ></path>

                        <circle
                            cx="9"
                            cy="7"
                            r="4"
                        ></circle>

                        <path
                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                        ></path>

                        <path
                            d="M16 3.13a4 4 0 0 1 0 7.75"
                        ></path>
                    </svg>

                </div>

                <div class="dashboard-card-info">

                    <span>
                        Usuários
                    </span>

                    <strong>
                        {{ $totalUsuarios ?? 0 }}
                    </strong>

                </div>

            </article>


            {{-- PRODUTOS --}}
            <article class="dashboard-card">

                <div class="dashboard-card-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M3 9l9-6 9 6"></path>

                        <path
                            d="M21 9v10l-9 3-9-3V9"
                        ></path>

                        <path d="M3 9l9 4 9-4"></path>

                        <path d="M12 13v9"></path>
                    </svg>

                </div>

                <div class="dashboard-card-info">

                    <span>
                        Produtos
                    </span>

                    <strong>
                        {{ $totalProdutos ?? 0 }}
                    </strong>

                </div>

            </article>


            {{-- VENDAS --}}
            <article class="dashboard-card">

                <div class="dashboard-card-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle
                            cx="9"
                            cy="20"
                            r="1"
                        ></circle>

                        <circle
                            cx="19"
                            cy="20"
                            r="1"
                        ></circle>

                        <path
                            d="M3 4h2l2.4 11.2A2 2 0 0 0 9.4 17H18a2 2 0 0 0 2-1.6L21 9H7"
                        ></path>
                    </svg>

                </div>

                <div class="dashboard-card-info">

                    <span>
                        Vendas
                    </span>

                    <strong>
                        {{ $totalVendas ?? 0 }}
                    </strong>

                </div>

            </article>


            {{-- FATURAMENTO --}}
            <article class="dashboard-card">

                <div class="dashboard-card-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        ></circle>

                        <path
                            d="M16 8h-6a2 2 0 0 0 0 4h4a2 2 0 0 1 0 4H8"
                        ></path>

                        <path d="M12 6v12"></path>
                    </svg>

                </div>

                <div class="dashboard-card-info">

                    <span>
                        Faturamento
                    </span>

                    <strong>
                        R$
                        {{ number_format(
                            $faturamento ?? 0,
                            2,
                            ',',
                            '.'
                        ) }}
                    </strong>

                </div>

            </article>

        </section>


        {{-- =====================================================
            CONTEÚDO
        ====================================================== --}}

        <section class="dashboard-grid">

            {{-- VENDAS --}}
            <article class="dashboard-panel dashboard-panel-large">

                <div class="dashboard-panel-header">

                    <div>

                        <h2>
                            Vendas
                        </h2>

                        <p>
                            Vendas realizadas recentemente
                        </p>

                    </div>

                    <a href="{{ url('/admin/vendas') }}">
                        Ver todas
                    </a>

                </div>


                <div class="dashboard-empty">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                    >
                        <path d="M4 19V9"></path>
                        <path d="M10 19V5"></path>
                        <path d="M16 19v-7"></path>
                        <path d="M22 19H2"></path>
                    </svg>

                    <span>
                        Os dados de vendas aparecerão aqui.
                    </span>

                </div>

            </article>


            {{-- ATALHOS --}}
            <article class="dashboard-panel">

                <div class="dashboard-panel-header">

                    <div>

                        <h2>
                            Acesso rápido
                        </h2>

                        <p>
                            Principais áreas do sistema
                        </p>

                    </div>

                </div>


                <div class="dashboard-shortcuts">

                    <a
                        href="{{ url('/admin/usuarios') }}"
                        class="dashboard-shortcut"
                    >

                        <div class="dashboard-shortcut-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="9"
                                    cy="7"
                                    r="4"
                                ></circle>

                                <path
                                    d="M2 21a7 7 0 0 1 14 0"
                                ></path>
                            </svg>

                        </div>

                        <span>
                            Usuários
                        </span>

                        <strong>
                            ›
                        </strong>

                    </a>


                    <a
                        href="{{ url('/admin/produtos') }}"
                        class="dashboard-shortcut"
                    >

                        <div class="dashboard-shortcut-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M3 9l9-6 9 6"></path>

                                <path
                                    d="M21 9v10l-9 3-9-3V9"
                                ></path>

                                <path d="M3 9l9 4 9-4"></path>
                            </svg>

                        </div>

                        <span>
                            Produtos
                        </span>

                        <strong>
                            ›
                        </strong>

                    </a>


                    <a
                        href="{{ url('/admin/categorias') }}"
                        class="dashboard-shortcut"
                    >

                        <div class="dashboard-shortcut-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="16"
                                    rx="2"
                                ></rect>

                                <path d="M8 8h9"></path>
                                <path d="M8 12h9"></path>
                                <path d="M8 16h9"></path>
                            </svg>

                        </div>

                        <span>
                            Categorias
                        </span>

                        <strong>
                            ›
                        </strong>

                    </a>


                    <a
                        href="{{ url('/admin/vendas') }}"
                        class="dashboard-shortcut"
                    >

                        <div class="dashboard-shortcut-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="9"
                                    cy="20"
                                    r="1"
                                ></circle>

                                <circle
                                    cx="19"
                                    cy="20"
                                    r="1"
                                ></circle>

                                <path
                                    d="M3 4h2l2.4 11.2A2 2 0 0 0 9.4 17H18a2 2 0 0 0 2-1.6L21 9H7"
                                ></path>
                            </svg>

                        </div>

                        <span>
                            Vendas
                        </span>

                        <strong>
                            ›
                        </strong>

                    </a>

                </div>

            </article>

        </section>

    </div>

@endsection