@extends('layouts.admin')

@section('title', 'Novo usuário')

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
                href="{{ route('admin.usuarios.index') }}"
                class="admin-back-link"
            >
                ← Voltar
            </a>


            <h1>
                Novo usuário
            </h1>

            <p>
                Cadastre um novo usuário na D-tech.
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
        action="{{ route('admin.usuarios.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-user-form-card admin-user-form"
        data-cep-url="{{ url('/api/cep') }}"
    >

        @csrf


        @include('admin.users._form')


        <div class="admin-form-actions">

            <a
                href="{{ route('admin.usuarios.index') }}"
                class="admin-form-cancel"
            >
                Cancelar
            </a>


            <button
                type="submit"
                class="admin-form-submit"
            >
                Cadastrar usuário
            </button>

        </div>

    </form>

</div>

@endsection