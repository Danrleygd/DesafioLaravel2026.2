@extends('layouts.admin')

@section('title', 'Editar administrador')


@push('styles')

    @vite([
        'resources/css/adminUsers.css',
        'resources/js/adminUsers.js'
    ])

@endpush


@section('content')

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
                Editar administrador
            </h1>

            <p>
                Atualize os dados de
                {{ $administrador->nome }}.
            </p>

        </div>

    </header>


    @if($errors->any())

        <div class="admin-alert admin-alert-error">

            <strong>
                Existem campos que precisam ser corrigidos.
            </strong>

        </div>

    @endif


    <form
        action="{{ route(
            'admin.administradores.update',
            $administrador
        ) }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-user-form-card admin-user-form"
        data-cep-url="{{ url('/api/cep') }}"
    >

        @csrf
        @method('PUT')


        @include(
            'admin.administrators._form'
        )


        <div class="admin-form-actions">

            <a
                href="{{ route('admin.administradores.index') }}"
                class="admin-form-cancel"
            >
                Cancelar
            </a>


            <button
                type="submit"
                class="admin-form-submit"
            >
                Salvar alterações
            </button>

        </div>

    </form>

</div>

@endsection