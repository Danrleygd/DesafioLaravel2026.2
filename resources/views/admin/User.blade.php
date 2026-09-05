@extends('layouts.admin')

@section('title', 'Usuários')

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

            <h1>Usuários</h1>

            <p>
                Gerencie os usuários cadastrados no sistema.
            </p>

        </div>


        <div class="admin-users-breadcrumb">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <span>›</span>

            <strong>
                Usuários
            </strong>

        </div>

    </header>


    {{-- =========================================================
        ALERTAS
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
        CARDS
    ========================================================== --}}

    <section class="admin-users-stats">

        <article class="admin-users-stat-card">

            <div class="admin-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle cx="9" cy="8" r="3"></circle>
                    <circle cx="17" cy="9" r="2"></circle>
                    <path d="M3 20a6 6 0 0 1 12 0"></path>
                    <path d="M15 15a5 5 0 0 1 6 5"></path>
                </svg>

            </div>


            <div>

                <span>
                    Total de usuários
                </span>

                <strong>
                    {{ $totalUsuarios }}
                </strong>

                <small>
                    usuários comuns cadastrados
                </small>

            </div>

        </article>


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
                        cy="7"
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
                    Novos hoje
                </span>

                <strong>
                    {{ $novosHoje }}
                </strong>

                <small>
                    cadastros realizados hoje
                </small>

            </div>

        </article>


        <article class="admin-users-stat-card">

            <div class="admin-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        d="M3 11.5 12 4l9 7.5"
                    ></path>

                    <path
                        d="M5 10v10h14V10"
                    ></path>

                    <path
                        d="M9 20v-6h6v6"
                    ></path>
                </svg>

            </div>


            <div>

                <span>
                    Com endereço
                </span>

                <strong>
                    {{ $usuariosComEndereco }}
                </strong>

                <small>
                    usuários com endereço cadastrado
                </small>

            </div>

        </article>

    </section>


    {{-- =========================================================
        TABELA
    ========================================================== --}}

    <section class="admin-users-card">

        <form
            action="{{ route('admin.usuarios.index') }}"
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
                    placeholder="Pesquisar por nome, e-mail ou CPF..."
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
                    href="{{ route('admin.usuarios.index') }}"
                    class="admin-clear-button"
                >
                    Limpar
                </a>

            @endif


            <a
                href="{{ route('admin.usuarios.create') }}"
                class="admin-new-user"
            >
                <span>+</span>
                Novo usuário
            </a>

        </form>


        <div class="admin-users-table-wrapper">

            <table class="admin-users-table">

                <thead>

                    <tr>
                        <th>Usuário</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Cadastro</th>

                        <th class="admin-actions-column">
                            Ações
                        </th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($usuarios as $usuario)

                        @php

                            $foto = null;

                            if ($usuario->foto) {

                                $foto =
                                    str_starts_with($usuario->foto, 'http://')
                                    ||
                                    str_starts_with($usuario->foto, 'https://')
                                        ? $usuario->foto
                                        : asset(
                                            'storage/' .
                                            ltrim(
                                                $usuario->foto,
                                                '/'
                                            )
                                        );
                            }


                            $partes =
                                preg_split(
                                    '/\s+/',
                                    trim($usuario->nome)
                                );


                            $iniciais =
                                strtoupper(
                                    substr(
                                        $partes[0] ?? 'U',
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


                            $cpf =
                                preg_replace(
                                    '/\D/',
                                    '',
                                    $usuario->cpf ?? ''
                                );


                            $cpfMascarado =
                                strlen($cpf) === 11
                                    ? '***.***.***-' . substr($cpf, -2)
                                    : 'Não informado';


                            $endereco =
                                $usuario
                                    ->enderecos
                                    ->first();

                        @endphp


                        <tr>

                            <td>

                                <div class="admin-user-info">

                                    <div class="admin-user-avatar">

                                        @if($foto)

                                            <img
                                                src="{{ $foto }}"
                                                alt="{{ $usuario->nome }}"
                                            >

                                        @else

                                            {{ $iniciais }}

                                        @endif

                                    </div>


                                    <div>

                                        <strong>
                                            {{ $usuario->nome }}
                                        </strong>

                                        <span>
                                            CPF:
                                            {{ $cpfMascarado }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <td>
                                {{ $usuario->email }}
                            </td>


                            <td>
                                {{ $usuario->telefone }}
                            </td>


                            <td>

                                @if($endereco)

                                    {{ $endereco->cidade }}
                                    /
                                    {{ $endereco->estado }}

                                @else

                                    —

                                @endif

                            </td>


                            <td>

                                {{ $usuario->created_at
                                    ? $usuario->created_at->format('d/m/Y')
                                    : '—'
                                }}

                            </td>


                            <td>

                                <div class="admin-user-actions">

                                    <button
                                        type="button"
                                        class="admin-user-actions-button"
                                        aria-label="Ações"
                                    >
                                        ⋮
                                    </button>


                                    <div class="admin-user-actions-menu">

                                        <a
                                            href="{{ route(
                                                'admin.usuarios.show',
                                                $usuario
                                            ) }}"
                                        >
                                            Ver detalhes
                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.usuarios.edit',
                                                $usuario
                                            ) }}"
                                        >
                                            Editar
                                        </a>


                                        <div class="admin-action-divider"></div>


                                        <form
                                            action="{{ route(
                                                'admin.usuarios.destroy',
                                                $usuario
                                            ) }}"
                                            method="POST"
                                            class="admin-delete-form"
                                        >

                                            @csrf
                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="admin-delete-user"
                                                data-user-name="{{ $usuario->nome }}"
                                            >
                                                Excluir
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="admin-users-empty"
                            >
                                Nenhum usuário encontrado.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <footer class="admin-users-footer">

            <span>

                @if($usuarios->total() > 0)

                    Mostrando
                    {{ $usuarios->firstItem() }}
                    a
                    {{ $usuarios->lastItem() }}
                    de
                    {{ $usuarios->total() }}
                    usuários

                @else

                    Nenhum usuário encontrado.

                @endif

            </span>


            @if($usuarios->lastPage() > 1)

                <div class="admin-pagination">

                    @if($usuarios->onFirstPage())

                        <span class="admin-page disabled">
                            ‹
                        </span>

                    @else

                        <a
                            href="{{ $usuarios->previousPageUrl() }}"
                            class="admin-page"
                        >
                            ‹
                        </a>

                    @endif


                    @for(
                        $pagina = 1;
                        $pagina <= $usuarios->lastPage();
                        $pagina++
                    )

                        <a
                            href="{{ $usuarios->url($pagina) }}"
                            class="admin-page
                            {{ $usuarios->currentPage() === $pagina ? 'active' : '' }}"
                        >
                            {{ $pagina }}
                        </a>

                    @endfor


                    @if($usuarios->hasMorePages())

                        <a
                            href="{{ $usuarios->nextPageUrl() }}"
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