@extends('layouts.admin')

@section('title', 'Administradores')


@push('styles')

    @vite([
        'resources/css/adminUsers.css',
        'resources/js/adminUsers.js'
    ])

@endpush


@section('content')

<div class="admin-users-page">

    {{-- =========================================================
        CABEÇALHO
    ========================================================== --}}

    <header class="admin-users-header">

        <div>

            <h1>
                Administradores
            </h1>

            <p>
                Gerencie os administradores da D-tech.
            </p>

        </div>


        <div class="admin-users-breadcrumb">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <span>
                ›
            </span>

            <strong>
                Administradores
            </strong>

        </div>

    </header>


    {{-- =========================================================
        MENSAGENS
    ========================================================== --}}

    @if(session('success'))

        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="admin-alert admin-alert-error">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        ESTATÍSTICAS
    ========================================================== --}}

    <section class="admin-users-stats">


        {{-- TOTAL --}}
        <article class="admin-users-stat-card">

            <div class="admin-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"
                    ></path>

                    <circle
                        cx="12"
                        cy="10"
                        r="2"
                    ></circle>
                </svg>

            </div>


            <div>

                <span>
                    Total de administradores
                </span>

                <strong>
                    {{ $totalAdministradores }}
                </strong>

                <small>
                    cadastrados no sistema
                </small>

            </div>

        </article>


        {{-- CRIADOS POR MIM --}}
        <article class="admin-users-stat-card">

            <div class="admin-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle
                        cx="10"
                        cy="8"
                        r="4"
                    ></circle>

                    <path
                        d="M3 21a7 7 0 0 1 14 0"
                    ></path>

                    <path d="M19 8v6"></path>
                    <path d="M16 11h6"></path>
                </svg>

            </div>


            <div>

                <span>
                    Criados por mim
                </span>

                <strong>
                    {{ $criadosPorMim }}
                </strong>

                <small>
                    administradores gerenciáveis
                </small>

            </div>

        </article>


        {{-- NOVOS HOJE --}}
        <article class="admin-users-stat-card">

            <div class="admin-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    ></circle>

                    <path
                        d="M12 7v5l3 2"
                    ></path>
                </svg>

            </div>


            <div>

                <span>
                    Novos hoje
                </span>

                <strong>
                    {{ $novosHoje }}
                </strong>

                <small>
                    cadastrados hoje
                </small>

            </div>

        </article>

    </section>


    {{-- =========================================================
        TABELA
    ========================================================== --}}

    <section class="admin-users-card">


        {{-- FILTROS --}}
        <form
            action="{{ route('admin.administradores.index') }}"
            method="GET"
            class="admin-users-filters"
        >

            <div class="admin-search">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <path
                        d="m20 20-4-4"
                    ></path>
                </svg>


                <input
                    type="text"
                    name="busca"
                    value="{{ request('busca') }}"
                    placeholder="Pesquisar nome, e-mail ou CPF..."
                >

            </div>


            <button
                type="submit"
                class="admin-filter-button"
            >
                Filtrar
            </button>


            @if(request()->filled('busca'))

                <a
                    href="{{ route('admin.administradores.index') }}"
                    class="admin-clear-button"
                >
                    Limpar
                </a>

            @endif


            <a
                href="{{ route('admin.administradores.create') }}"
                class="admin-new-user"
            >
                <span>+</span>

                Novo administrador
            </a>

        </form>


        {{-- =====================================================
            TABELA
        ====================================================== --}}

        <div class="admin-users-table-wrapper">

            <table class="admin-users-table">

                <thead>

                    <tr>

                        <th>
                            Administrador
                        </th>

                        <th>
                            E-mail
                        </th>

                        <th>
                            Criado por
                        </th>

                        <th>
                            Cadastro
                        </th>

                        <th class="admin-actions-column">
                            Ações
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($administradores as $administrador)

                        @php

                            $foto = null;

                            if ($administrador->foto) {

                                $foto =
                                    str_starts_with(
                                        $administrador->foto,
                                        'http://'
                                    )
                                    ||
                                    str_starts_with(
                                        $administrador->foto,
                                        'https://'
                                    )
                                        ? $administrador->foto
                                        : asset(
                                            'storage/' .
                                            ltrim(
                                                $administrador->foto,
                                                '/'
                                            )
                                        );
                            }


                            $partes =
                                preg_split(
                                    '/\s+/',
                                    trim(
                                        $administrador->nome
                                    )
                                );


                            $iniciais =
                                strtoupper(
                                    substr(
                                        $partes[0] ?? 'A',
                                        0,
                                        1
                                    )
                                );


                            if (count($partes) > 1) {

                                $iniciais .=
                                    strtoupper(
                                        substr(
                                            end($partes),
                                            0,
                                            1
                                        )
                                    );
                            }


                            /*
                             * Regra RF006
                             */
                            $propriaConta =
                                Auth::id()
                                ===
                                $administrador->id;


                            $criadoPorMim =
                                $administrador->criador_id
                                ===
                                Auth::id();


                            $podeGerenciar =
                                $propriaConta
                                ||
                                $criadoPorMim;

                        @endphp


                        <tr>

                            {{-- ADMINISTRADOR --}}
                            <td>

                                <div class="admin-user-info">

                                    <div class="admin-user-avatar">

                                        @if($foto)

                                            <img
                                                src="{{ $foto }}"
                                                alt="{{ $administrador->nome }}"
                                            >

                                        @else

                                            {{ $iniciais }}

                                        @endif

                                    </div>


                                    <div>

                                        <strong>
                                            {{ $administrador->nome }}
                                        </strong>


                                        @if($propriaConta)

                                            <span>
                                                Sua conta
                                            </span>

                                        @else

                                            <span>
                                                Administrador
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- EMAIL --}}
                            <td>

                                {{ $administrador->email }}

                            </td>


                            {{-- CRIADOR --}}
                            <td>

                                @if($administrador->creator)

                                    {{ $administrador->creator->nome }}

                                @elseif($propriaConta)

                                    —

                                @else

                                    Não informado

                                @endif

                            </td>


                            {{-- CADASTRO --}}
                            <td>

                                {{ $administrador->created_at
                                    ? $administrador
                                        ->created_at
                                        ->format('d/m/Y')
                                    : '—'
                                }}

                            </td>


                            {{-- AÇÕES --}}
                            <td>

                                <div class="admin-user-actions">

                                    <button
                                        type="button"
                                        class="admin-user-actions-button"
                                    >
                                        ⋮
                                    </button>


                                    <div class="admin-user-actions-menu">

                                        {{-- VER --}}
                                        <a
                                            href="{{ route(
                                                'admin.administradores.show',
                                                $administrador
                                            ) }}"
                                        >
                                            Ver detalhes
                                        </a>


                                        @if($podeGerenciar)

                                            {{-- EDITAR --}}
                                            <a
                                                href="{{ route(
                                                    'admin.administradores.edit',
                                                    $administrador
                                                ) }}"
                                            >
                                                Editar
                                            </a>


                                            <div class="admin-action-divider"></div>


                                            {{-- EXCLUIR --}}
                                            <form
                                                action="{{ route(
                                                    'admin.administradores.destroy',
                                                    $administrador
                                                ) }}"
                                                method="POST"
                                                class="admin-delete-form"
                                            >

                                                @csrf
                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="admin-delete-user"
                                                    data-user-name="{{ $administrador->nome }}"
                                                >
                                                    Excluir
                                                </button>

                                            </form>

                                        @else

                                            <div class="admin-action-divider"></div>

                                            <span
                                                style="
                                                    display:block;
                                                    padding:8px 9px;
                                                    font-size:9px;
                                                    color:#92899a;
                                                "
                                            >
                                                Somente visualização
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="admin-users-empty"
                            >
                                Nenhum administrador encontrado.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINAÇÃO
        ====================================================== --}}

        <footer class="admin-users-footer">

            <span>

                @if(
                    $administradores->total()
                    > 0
                )

                    Mostrando
                    {{ $administradores->firstItem() }}
                    a
                    {{ $administradores->lastItem() }}
                    de
                    {{ $administradores->total() }}
                    administradores

                @else

                    Nenhum administrador encontrado.

                @endif

            </span>


            @if(
                $administradores->lastPage()
                > 1
            )

                <div class="admin-pagination">

                    @if(
                        $administradores->onFirstPage()
                    )

                        <span class="admin-page disabled">
                            ‹
                        </span>

                    @else

                        <a
                            href="{{ $administradores->previousPageUrl() }}"
                            class="admin-page"
                        >
                            ‹
                        </a>

                    @endif


                    @for(
                        $pagina = 1;
                        $pagina <= $administradores->lastPage();
                        $pagina++
                    )

                        <a
                            href="{{ $administradores->url($pagina) }}"
                            class="
                                admin-page
                                {{ $administradores->currentPage() === $pagina
                                    ? 'active'
                                    : ''
                                }}
                            "
                        >
                            {{ $pagina }}
                        </a>

                    @endfor


                    @if(
                        $administradores->hasMorePages()
                    )

                        <a
                            href="{{ $administradores->nextPageUrl() }}"
                            class="admin-page"
                        >
                            ›
                        </a>

                    @else

                        <span class="admin-page disabled">
                            ›
                        </span>

                    @endif

                </div>

            @endif

        </footer>

    </section>

</div>

@endsection