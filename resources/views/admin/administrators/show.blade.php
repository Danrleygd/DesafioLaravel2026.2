@extends('layouts.admin')

@section('title', 'Detalhes do administrador')


@push('styles')

    @vite([
        'resources/css/adminUsers.css'
    ])

@endpush


@section('content')

@php

    $endereco =
        $administrador
            ->enderecos
            ->first();


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


<div class="admin-user-form-page">

    <header class="admin-form-header">

        <div>

            <a
                href="{{ route('admin.administradores.index') }}"
                class="admin-back-link"
            >
                ← Voltar
            </a>


            <h1>
                Detalhes do administrador
            </h1>

            <p>
                Informações da conta administrativa.
            </p>

        </div>


        @if($podeGerenciar)

            <a
                href="{{ route(
                    'admin.administradores.edit',
                    $administrador
                ) }}"
                class="admin-form-submit"
            >
                Editar administrador
            </a>

        @endif

    </header>


    <section class="admin-user-details-card">

        <div class="admin-detail-profile">

            <div class="admin-detail-avatar">

                @if($foto)

                    <img
                        src="{{ $foto }}"
                        alt="{{ $administrador->nome }}"
                    >

                @else

                    A

                @endif

            </div>


            <div>

                <h2>
                    {{ $administrador->nome }}
                </h2>


                <span class="admin-user-badge admin-user-badge-admin">
                    Administrador
                </span>

            </div>

        </div>


        <div class="admin-details-grid">

            <div class="admin-detail-item">

                <span>
                    E-mail
                </span>

                <strong>
                    {{ $administrador->email }}
                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    CPF
                </span>

                <strong>
                    {{ $administrador->cpf }}
                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Telefone
                </span>

                <strong>
                    {{ $administrador->telefone }}
                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Data de nascimento
                </span>

                <strong>

                    {{ $administrador->data_nascimento
                        ? $administrador
                            ->data_nascimento
                            ->format('d/m/Y')
                        : '—'
                    }}

                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Criado por
                </span>

                <strong>

                    {{ $administrador->creator?->nome
                        ?? 'Não informado'
                    }}

                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Data de cadastro
                </span>

                <strong>

                    {{ $administrador->created_at
                        ? $administrador
                            ->created_at
                            ->format('d/m/Y H:i')
                        : '—'
                    }}

                </strong>

            </div>

        </div>


        {{-- =====================================================
            ENDEREÇO
        ====================================================== --}}

        <div class="admin-address-section">

            <h3>
                Endereço
            </h3>


            @if($endereco)

                <div class="admin-address-list">

                    <div class="admin-address-card">

                        <strong>

                            {{ $endereco->logradouro }},
                            {{ $endereco->numero }}

                        </strong>


                        <span>

                            {{ $endereco->bairro }}

                            —

                            {{ $endereco->cidade }}/{{ $endereco->estado }}

                        </span>


                        <span>

                            CEP:
                            {{ $endereco->cep }}

                        </span>


                        @if($endereco->complemento)

                            <small>

                                Complemento:
                                {{ $endereco->complemento }}

                            </small>

                        @endif

                    </div>

                </div>

            @else

                <p>
                    Nenhum endereço cadastrado.
                </p>

            @endif

        </div>

    </section>

</div>

@endsection