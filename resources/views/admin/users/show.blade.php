@extends('layouts.admin')

@section('title', 'Detalhes do usuário')

@push('styles')
    @vite([
        'resources/css/adminUsers.css'
    ])
@endpush


@section('content')

@php

    $endereco =
        $usuario
            ->enderecos
            ->first();


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


    $nome =
        preg_split(
            '/\s+/',
            trim($usuario->nome)
        );


    $iniciais =
        strtoupper(
            substr(
                $nome[0] ?? 'U',
                0,
                1
            )
        );

@endphp


<div class="admin-user-form-page">

    <header class="admin-form-header">

        <div>

            <a
                href="{{ route('admin.usuarios.index') }}"
                class="admin-back-link"
            >
                ← Voltar
            </a>


            <h1>
                Detalhes do usuário
            </h1>

            <p>
                Informações cadastradas no sistema.
            </p>

        </div>


        <a
            href="{{ route(
                'admin.usuarios.edit',
                $usuario
            ) }}"
            class="admin-form-submit"
        >
            Editar usuário
        </a>

    </header>


    <section class="admin-user-details-card">

        <div class="admin-detail-profile">

            <div class="admin-detail-avatar">

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

                <h2>
                    {{ $usuario->nome }}
                </h2>

                <span class="admin-user-badge admin-user-badge-normal">
                    Usuário
                </span>

            </div>

        </div>


        <div class="admin-details-grid">

            <div class="admin-detail-item">
                <span>E-mail</span>
                <strong>{{ $usuario->email }}</strong>
            </div>


            <div class="admin-detail-item">
                <span>CPF</span>
                <strong>{{ $usuario->cpf }}</strong>
            </div>


            <div class="admin-detail-item">
                <span>Telefone</span>
                <strong>{{ $usuario->telefone }}</strong>
            </div>


            <div class="admin-detail-item">

                <span>
                    Data de nascimento
                </span>

                <strong>

                    {{ $usuario->data_nascimento
                        ? $usuario->data_nascimento->format('d/m/Y')
                        : '—'
                    }}

                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Saldo
                </span>

                <strong>

                    R$
                    {{ number_format(
                        $usuario->saldo ?? 0,
                        2,
                        ',',
                        '.'
                    ) }}

                </strong>

            </div>


            <div class="admin-detail-item">

                <span>
                    Cadastrado em
                </span>

                <strong>

                    {{ $usuario->created_at
                        ? $usuario->created_at->format('d/m/Y H:i')
                        : '—'
                    }}

                </strong>

            </div>

        </div>


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