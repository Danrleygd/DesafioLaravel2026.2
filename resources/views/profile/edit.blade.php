<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>Meu Perfil - D-tech</title>


    @vite([
    'resources/css/app.css',
    'resources/css/navLanding.css',
    'resources/css/profile.css'
    ])

</head>


@php

$user = $user ?? auth()->user();

$endereco = $user
->enderecos
->first();


/*
|--------------------------------------------------------------------------
| FOTO
|--------------------------------------------------------------------------
*/

$fotoPerfil = null;


if ($user->foto) {

if (
str_starts_with(
$user->foto,
'http://'
)
||
str_starts_with(
$user->foto,
'https://'
)
) {

$fotoPerfil =
$user->foto;

} else {

$fotoPerfil =
asset(
'storage/' .
ltrim(
$user->foto,
'/'
)
);
}
}


/*
|--------------------------------------------------------------------------
| INICIAIS
|--------------------------------------------------------------------------
*/

$partesNome =
preg_split(
'/\s+/',
trim($user->nome)
);


$iniciais =
strtoupper(
substr(
$partesNome[0] ?? 'U',
0,
1
)
);


if (
count($partesNome) > 1
) {

$iniciais .=
strtoupper(
substr(
end($partesNome),
0,
1
)
);
}

@endphp


<body class="profile-body">

    {{-- =========================================================
        NAVBAR NORMAL
    ========================================================== --}}

    <x-nav-landing />


    {{-- =========================================================
        CONTEÚDO
    ========================================================== --}}

    <main class="profile-page">

        <div class="profile-container">


            {{-- =====================================================
                CABEÇALHO
            ====================================================== --}}

            <header class="profile-header">

                <div>

                    <span class="profile-header-label">
                        MINHA CONTA
                    </span>

                    <h1>
                        Meu Perfil
                    </h1>

                    <p>
                        Gerencie suas informações pessoais e configurações da conta.
                    </p>

                </div>


                <div class="profile-header-actions">

                    {{-- VOLTAR PARA HOME --}}
                    <a
                        href="{{ url('/') }}"
                        class="profile-button profile-button-secondary">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 11.5 12 4l9 7.5"></path>
                            <path d="M5 10v10h14V10"></path>
                            <path d="M9 20v-6h6v6"></path>
                        </svg>

                        Página inicial

                    </a>


                    {{-- MEUS PRODUTOS --}}
                    @if(
                    auth()->user()->tipo !== 'administrador'
                    )

                    <a
                        href="{{ route('meus-produtos.index') }}"
                        class="profile-button profile-button-secondary">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M3 7l9-4 9 4-9 4-9-4Z"></path>
                            <path d="M3 7v10l9 4 9-4V7"></path>
                            <path d="M12 11v10"></path>
                        </svg>

                        Meus Produtos

                    </a>


                    <a
                        href="{{ route('meus-produtos.create') }}"
                        class="pm-primary-button">
                        + Novo produto
                    </a>

                    @endif

                </div>

            </header>


            {{-- =====================================================
                MENSAGENS
            ====================================================== --}}

            @if(session('status'))

            <div class="profile-alert profile-alert-success">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"></circle>

                    <path
                        d="m8 12 3 3 5-6"></path>
                </svg>

                {{ session('status') }}

            </div>

            @endif


            @if(session('success'))

            <div class="profile-alert profile-alert-success">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"></circle>

                    <path
                        d="m8 12 3 3 5-6"></path>
                </svg>

                {{ session('success') }}

            </div>

            @endif


            @if(session('error'))

            <div class="profile-alert profile-alert-error">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"></circle>

                    <path d="M12 8v5"></path>
                    <path d="M12 16h.01"></path>
                </svg>

                {{ session('error') }}

            </div>

            @endif


            @if($errors->any())

            <div class="profile-alert profile-alert-error">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"></circle>

                    <path d="M12 8v5"></path>
                    <path d="M12 16h.01"></path>
                </svg>


                <div>

                    <strong>
                        Existem campos que precisam ser corrigidos.
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                        @endforeach

                    </ul>

                </div>

            </div>

            @endif


            {{-- =====================================================
                GRID PRINCIPAL
            ====================================================== --}}

            <div class="profile-layout">


                {{-- =================================================
                    COLUNA ESQUERDA
                ================================================== --}}

                <aside class="profile-sidebar-card">

                    {{-- FOTO --}}
                    <div class="profile-avatar-wrapper">

                        <div
                            class="profile-avatar"
                            id="profileAvatar">

                            @if($fotoPerfil)

                            <img
                                src="{{ $fotoPerfil }}"
                                alt="{{ $user->nome }}"
                                id="profileAvatarImage">

                            <span
                                id="profileAvatarInitials"
                                hidden>
                                {{ $iniciais }}
                            </span>

                            @else

                            <span
                                id="profileAvatarInitials">
                                {{ $iniciais }}
                            </span>

                            <img
                                src=""
                                alt=""
                                id="profileAvatarImage"
                                hidden>

                            @endif

                        </div>


                        <div class="profile-avatar-status"></div>

                    </div>


                    <h2>
                        {{ $user->nome }}
                    </h2>


                    <span class="profile-user-type">

                        {{ $user->tipo === 'administrador'
                            ? 'Administrador'
                            : 'Usuário'
                        }}

                    </span>


                    <p class="profile-user-email">
                        {{ $user->email }}
                    </p>


                    <div class="profile-divider"></div>


                    {{-- INFORMAÇÕES --}}
                    <div class="profile-summary-list">


                        <div class="profile-summary-item">

                            <div class="profile-summary-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M4 4h16v16H4z"></path>
                                    <path d="m4 7 8 6 8-6"></path>
                                </svg>

                            </div>


                            <div>

                                <span>
                                    E-mail
                                </span>

                                <strong>
                                    {{ $user->email }}
                                </strong>

                            </div>

                        </div>


                        <div class="profile-summary-item">

                            <div class="profile-summary-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path
                                        d="M22 16.9v3a2 2 0 0 1-2.18 2
                                        19.79 19.79 0 0 1-8.63-3.07
                                        19.5 19.5 0 0 1-6-6
                                        A19.79 19.79 0 0 1
                                        2.12 4.18 2 2 0 0 1
                                        4.11 2h3a2 2 0 0 1
                                        2 1.72c.12.9.33 1.78.62 2.63
                                        a2 2 0 0 1-.45 2.11L8
                                        9.73a16 16 0 0 0 6 6l1.27-1.27
                                        a2 2 0 0 1 2.11-.45
                                        c.85.29 1.73.5 2.63.62
                                        A2 2 0 0 1 22 16.9z"></path>
                                </svg>

                            </div>


                            <div>

                                <span>
                                    Telefone
                                </span>

                                <strong>

                                    {{ $user->telefone
                                        ?: 'Não informado'
                                    }}

                                </strong>

                            </div>

                        </div>


                        <div class="profile-summary-item">

                            <div class="profile-summary-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path
                                        d="M20 10c0 5-8 11-8 11S4 15 4 10
                                        a8 8 0 1 1 16 0Z"></path>

                                    <circle
                                        cx="12"
                                        cy="10"
                                        r="2.5"></circle>
                                </svg>

                            </div>


                            <div>

                                <span>
                                    Localização
                                </span>

                                <strong>

                                    @if($endereco)

                                    {{ $endereco->cidade }}
                                    /
                                    {{ $endereco->estado }}

                                    @else

                                    Não informada

                                    @endif

                                </strong>

                            </div>

                        </div>


                        <div class="profile-summary-item">

                            <div class="profile-summary-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"></circle>

                                    <path d="M8 12h8"></path>
                                    <path d="M12 8v8"></path>
                                </svg>

                            </div>


                            <div>

                                <span>
                                    Saldo
                                </span>

                                <strong>

                                    R$
                                    {{ number_format(
                                        $user->saldo ?? 0,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </strong>

                            </div>

                        </div>

                    </div>


                    @if(
                    Route::has(
                    'meus-produtos.index'
                    )
                    &&
                    $user->tipo !== 'administrador'
                    )

                    <a
                        href="{{ route('meus-produtos.index') }}"
                        class="profile-products-card-button">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M3 7l9-4 9 4-9 4-9-4Z"></path>
                            <path d="M3 7v10l9 4 9-4V7"></path>
                            <path d="M12 11v10"></path>
                        </svg>

                        Gerenciar meus produtos

                        <span>
                            →
                        </span>

                    </a>

                    @endif

                </aside>


                {{-- =================================================
                    COLUNA DIREITA
                ================================================== --}}

                <div class="profile-content">


                    {{-- =============================================
                        DADOS PESSOAIS
                    ============================================== --}}

                    <section class="profile-card">

                        <div class="profile-card-header">

                            <div class="profile-card-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle
                                        cx="12"
                                        cy="8"
                                        r="4"></circle>

                                    <path
                                        d="M4 21a8 8 0 0 1 16 0"></path>
                                </svg>

                            </div>


                            <div>

                                <h2>
                                    Informações pessoais
                                </h2>

                                <p>
                                    Atualize seus dados pessoais e sua foto.
                                </p>

                            </div>

                        </div>


                        <form
                            action="{{ route('profile.update') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="profile-form">

                            @csrf
                            @method('PATCH')


                            {{-- FOTO --}}
                            <div class="profile-photo-edit">

                                <div
                                    class="profile-photo-preview"
                                    id="photoPreview">

                                    @if($fotoPerfil)

                                    <img
                                        src="{{ $fotoPerfil }}"
                                        alt="{{ $user->nome }}"
                                        id="photoPreviewImage">

                                    <span
                                        id="photoPreviewPlaceholder"
                                        hidden>
                                        {{ $iniciais }}
                                    </span>

                                    @else

                                    <span
                                        id="photoPreviewPlaceholder">
                                        {{ $iniciais }}
                                    </span>

                                    <img
                                        src=""
                                        alt=""
                                        id="photoPreviewImage"
                                        hidden>

                                    @endif

                                </div>


                                <div class="profile-photo-actions">

                                    <label
                                        for="foto"
                                        class="profile-upload-button">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M12 16V4"></path>
                                            <path d="m7 9 5-5 5 5"></path>
                                            <path d="M5 20h14"></path>
                                        </svg>

                                        Alterar foto

                                    </label>


                                    <input
                                        type="file"
                                        name="foto"
                                        id="foto"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        hidden>


                                    <span>
                                        JPG, PNG ou WEBP. Máximo de 2 MB.
                                    </span>

                                    @error('foto')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>

                            </div>


                            <div class="profile-form-grid">


                                {{-- NOME --}}
                                <div class="profile-form-group profile-form-full">

                                    <label for="nome">
                                        Nome completo
                                    </label>

                                    <input
                                        type="text"
                                        name="nome"
                                        id="nome"
                                        value="{{ old(
                                            'nome',
                                            $user->nome
                                        ) }}"
                                        required>

                                    @error('nome')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- EMAIL --}}
                                <div class="profile-form-group">

                                    <label for="email">
                                        E-mail
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old(
                                            'email',
                                            $user->email
                                        ) }}"
                                        required>

                                    @error('email')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- CPF --}}
                                <div class="profile-form-group">

                                    <label for="cpf">
                                        CPF
                                    </label>

                                    <input
                                        type="text"
                                        name="cpf"
                                        id="cpf"
                                        maxlength="14"
                                        value="{{ old(
                                            'cpf',
                                            $user->cpf
                                        ) }}">

                                    @error('cpf')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- TELEFONE --}}
                                <div class="profile-form-group">

                                    <label for="telefone">
                                        Telefone
                                    </label>

                                    <input
                                        type="text"
                                        name="telefone"
                                        id="telefone"
                                        maxlength="15"
                                        value="{{ old(
                                            'telefone',
                                            $user->telefone
                                        ) }}">

                                    @error('telefone')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- DATA --}}
                                <div class="profile-form-group">

                                    <label for="data_nascimento">
                                        Data de nascimento
                                    </label>

                                    <input
                                        type="date"
                                        name="data_nascimento"
                                        id="data_nascimento"
                                        value="{{ old(
                                            'data_nascimento',
                                            $user->data_nascimento
                                                ? $user
                                                    ->data_nascimento
                                                    ->format('Y-m-d')
                                                : ''
                                        ) }}">

                                    @error('data_nascimento')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>

                            </div>


                            <div class="profile-form-footer">

                                <button
                                    type="submit"
                                    class="profile-save-button">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M20 6 9 17l-5-5"></path>
                                    </svg>

                                    Salvar alterações

                                </button>

                            </div>

                        </form>

                    </section>


                    {{-- =============================================
                        ENDEREÇO
                    ============================================== --}}

                    <section class="profile-card">

                        <div class="profile-card-header">

                            <div class="profile-card-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path
                                        d="M20 10c0 5-8 11-8 11S4 15 4 10
                                        a8 8 0 1 1 16 0Z"></path>

                                    <circle
                                        cx="12"
                                        cy="10"
                                        r="2.5"></circle>
                                </svg>

                            </div>


                            <div>

                                <h2>
                                    Endereço
                                </h2>

                                <p>
                                    Digite o CEP para preencher o endereço automaticamente.
                                </p>

                            </div>

                        </div>


                        <form
                            action="{{ route('profile.address.store') }}"
                            method="POST"
                            class="profile-form"
                            id="addressForm"
                            data-cep-url="{{ url('/api/cep') }}">

                            @csrf


                            <div class="profile-form-grid">


                                {{-- CEP --}}
                                <div class="profile-form-group">

                                    <label for="cep">
                                        CEP
                                    </label>


                                    <div class="profile-cep-wrapper">

                                        <input
                                            type="text"
                                            name="cep"
                                            id="cep"
                                            maxlength="9"
                                            value="{{ old(
                                                'cep',
                                                $endereco?->cep
                                            ) }}"
                                            placeholder="00000-000"
                                            required>


                                        <span
                                            class="profile-cep-loading"
                                            id="cepLoading"
                                            hidden>
                                            Buscando...
                                        </span>

                                    </div>


                                    <span
                                        id="cepMessage"
                                        class="profile-cep-message"></span>


                                    @error('cep')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- NÚMERO --}}
                                <div class="profile-form-group">

                                    <label for="numero">
                                        Número
                                    </label>

                                    <input
                                        type="text"
                                        name="numero"
                                        id="numero"
                                        value="{{ old(
                                            'numero',
                                            $endereco?->numero
                                        ) }}"
                                        required>

                                    @error('numero')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- LOGRADOURO --}}
                                <div class="profile-form-group profile-form-full">

                                    <label for="logradouro">
                                        Logradouro
                                    </label>

                                    <input
                                        type="text"
                                        name="logradouro"
                                        id="logradouro"
                                        value="{{ old(
                                            'logradouro',
                                            $endereco?->logradouro
                                        ) }}"
                                        required>

                                    @error('logradouro')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- BAIRRO --}}
                                <div class="profile-form-group">

                                    <label for="bairro">
                                        Bairro
                                    </label>

                                    <input
                                        type="text"
                                        name="bairro"
                                        id="bairro"
                                        value="{{ old(
                                            'bairro',
                                            $endereco?->bairro
                                        ) }}"
                                        required>

                                    @error('bairro')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- CIDADE --}}
                                <div class="profile-form-group">

                                    <label for="cidade">
                                        Cidade
                                    </label>

                                    <input
                                        type="text"
                                        name="cidade"
                                        id="cidade"
                                        value="{{ old(
                                            'cidade',
                                            $endereco?->cidade
                                        ) }}"
                                        required>

                                    @error('cidade')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- ESTADO --}}
                                <div class="profile-form-group">

                                    <label for="estado">
                                        Estado
                                    </label>

                                    <input
                                        type="text"
                                        name="estado"
                                        id="estado"
                                        maxlength="2"
                                        value="{{ old(
                                            'estado',
                                            $endereco?->estado
                                        ) }}"
                                        required>

                                    @error('estado')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- COMPLEMENTO --}}
                                <div class="profile-form-group">

                                    <label for="complemento">
                                        Complemento
                                    </label>

                                    <input
                                        type="text"
                                        name="complemento"
                                        id="complemento"
                                        value="{{ old(
                                            'complemento',
                                            $endereco?->complemento
                                        ) }}"
                                        placeholder="Opcional">

                                    @error('complemento')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>

                            </div>


                            <div class="profile-form-footer">

                                <button
                                    type="submit"
                                    class="profile-save-button">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M20 6 9 17l-5-5"></path>
                                    </svg>

                                    Salvar endereço

                                </button>

                            </div>

                        </form>

                    </section>


                    {{-- =============================================
                        SENHA
                    ============================================== --}}

                    <section class="profile-card">

                        <div class="profile-card-header">

                            <div class="profile-card-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect
                                        x="4"
                                        y="10"
                                        width="16"
                                        height="11"
                                        rx="2"></rect>

                                    <path
                                        d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                </svg>

                            </div>


                            <div>

                                <h2>
                                    Segurança
                                </h2>

                                <p>
                                    Altere sua senha de acesso.
                                </p>

                            </div>

                        </div>


                        <form
                            action="{{ route('profile.password') }}"
                            method="POST"
                            class="profile-form">

                            @csrf
                            @method('PATCH')


                            <div class="profile-form-grid">


                                {{-- SENHA ATUAL --}}
                                <div class="profile-form-group profile-form-full">

                                    <label for="current_password">
                                        Senha atual
                                    </label>

                                    <input
                                        type="password"
                                        name="current_password"
                                        id="current_password"
                                        autocomplete="current-password"
                                        required>

                                    @error('current_password')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- NOVA SENHA --}}
                                <div class="profile-form-group">

                                    <label for="password">
                                        Nova senha
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        autocomplete="new-password"
                                        required>

                                    @error('password')

                                    <small class="profile-field-error">
                                        {{ $message }}
                                    </small>

                                    @enderror

                                </div>


                                {{-- CONFIRMAÇÃO --}}
                                <div class="profile-form-group">

                                    <label for="password_confirmation">
                                        Confirmar nova senha
                                    </label>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        autocomplete="new-password"
                                        required>

                                </div>

                            </div>


                            <div class="profile-form-footer">

                                <button
                                    type="submit"
                                    class="profile-save-button">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path
                                            d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                    </svg>

                                    Alterar senha

                                </button>

                            </div>

                        </form>

                    </section>


                    {{-- =============================================
                        EXCLUIR CONTA
                    ============================================== --}}

                    <section class="profile-card profile-danger-card">

                        <div class="profile-card-header">

                            <div class="profile-card-icon profile-danger-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M3 6h18"></path>
                                    <path d="M8 6V4h8v2"></path>
                                    <path d="M19 6l-1 14H6L5 6"></path>
                                    <path d="M10 11v5"></path>
                                    <path d="M14 11v5"></path>
                                </svg>

                            </div>


                            <div>

                                <h2>
                                    Excluir conta
                                </h2>

                                <p>
                                    Essa ação remove permanentemente sua conta.
                                </p>

                            </div>

                        </div>


                        <div class="profile-danger-content">

                            <div>

                                <strong>
                                    Tem certeza que deseja excluir sua conta?
                                </strong>

                                <p>
                                    Após a exclusão, seus dados não poderão ser recuperados.
                                </p>

                            </div>


                            <form
                                action="{{ route('profile.destroy') }}"
                                method="POST"
                                id="deleteAccountForm">

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="profile-delete-button">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M3 6h18"></path>
                                        <path d="M8 6V4h8v2"></path>
                                        <path d="M19 6l-1 14H6L5 6"></path>
                                    </svg>

                                    Excluir minha conta

                                </button>

                            </form>

                        </div>

                    </section>

                </div>

            </div>

        </div>

    </main>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                /*
                |--------------------------------------------------------------------------
                | PREVIEW DA FOTO
                |--------------------------------------------------------------------------
                */

                const fotoInput =
                    document.getElementById(
                        'foto'
                    );


                const previewImage =
                    document.getElementById(
                        'photoPreviewImage'
                    );


                const previewPlaceholder =
                    document.getElementById(
                        'photoPreviewPlaceholder'
                    );


                const avatarImage =
                    document.getElementById(
                        'profileAvatarImage'
                    );


                const avatarInitials =
                    document.getElementById(
                        'profileAvatarInitials'
                    );


                if (
                    fotoInput &&
                    previewImage
                ) {

                    fotoInput.addEventListener(
                        'change',
                        function() {

                            const file =
                                this.files[0];


                            if (!file) {
                                return;
                            }


                            const reader =
                                new FileReader();


                            reader.onload =
                                function(event) {

                                    const url =
                                        event.target.result;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | PREVIEW DO FORM
                                    |--------------------------------------------------------------------------
                                    */

                                    previewImage.src =
                                        url;

                                    previewImage.hidden =
                                        false;


                                    if (
                                        previewPlaceholder
                                    ) {

                                        previewPlaceholder.hidden =
                                            true;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | FOTO DA COLUNA ESQUERDA
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        avatarImage
                                    ) {

                                        avatarImage.src =
                                            url;

                                        avatarImage.hidden =
                                            false;
                                    }


                                    if (
                                        avatarInitials
                                    ) {

                                        avatarInitials.hidden =
                                            true;
                                    }
                                };


                            reader.readAsDataURL(
                                file
                            );
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CPF
                |--------------------------------------------------------------------------
                */

                const cpf =
                    document.getElementById(
                        'cpf'
                    );


                function formatarCpf(
                    valor
                ) {

                    valor =
                        valor
                        .replace(
                            /\D/g,
                            ''
                        )
                        .substring(
                            0,
                            11
                        );


                    valor =
                        valor.replace(
                            /(\d{3})(\d)/,
                            '$1.$2'
                        );


                    valor =
                        valor.replace(
                            /(\d{3})(\d)/,
                            '$1.$2'
                        );


                    valor =
                        valor.replace(
                            /(\d{3})(\d{1,2})$/,
                            '$1-$2'
                        );


                    return valor;
                }


                if (cpf) {

                    cpf.value =
                        formatarCpf(
                            cpf.value
                        );


                    cpf.addEventListener(
                        'input',
                        function() {

                            this.value =
                                formatarCpf(
                                    this.value
                                );
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | TELEFONE
                |--------------------------------------------------------------------------
                */

                const telefone =
                    document.getElementById(
                        'telefone'
                    );


                function formatarTelefone(
                    valor
                ) {

                    valor =
                        valor
                        .replace(
                            /\D/g,
                            ''
                        )
                        .substring(
                            0,
                            11
                        );


                    if (
                        valor.length <= 10
                    ) {

                        return valor
                            .replace(
                                /^(\d{2})(\d)/,
                                '($1) $2'
                            )
                            .replace(
                                /(\d{4})(\d)/,
                                '$1-$2'
                            );
                    }


                    return valor
                        .replace(
                            /^(\d{2})(\d)/,
                            '($1) $2'
                        )
                        .replace(
                            /(\d{5})(\d)/,
                            '$1-$2'
                        );
                }


                if (telefone) {

                    telefone.value =
                        formatarTelefone(
                            telefone.value
                        );


                    telefone.addEventListener(
                        'input',
                        function() {

                            this.value =
                                formatarTelefone(
                                    this.value
                                );
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CEP
                |--------------------------------------------------------------------------
                */

                const addressForm =
                    document.getElementById(
                        'addressForm'
                    );


                const cep =
                    document.getElementById(
                        'cep'
                    );


                const logradouro =
                    document.getElementById(
                        'logradouro'
                    );


                const bairro =
                    document.getElementById(
                        'bairro'
                    );


                const cidade =
                    document.getElementById(
                        'cidade'
                    );


                const estado =
                    document.getElementById(
                        'estado'
                    );


                const numero =
                    document.getElementById(
                        'numero'
                    );


                const cepLoading =
                    document.getElementById(
                        'cepLoading'
                    );


                const cepMessage =
                    document.getElementById(
                        'cepMessage'
                    );


                let ultimoCepConsultado =
                    null;


                function formatarCep(
                    valor
                ) {

                    valor =
                        valor
                        .replace(
                            /\D/g,
                            ''
                        )
                        .substring(
                            0,
                            8
                        );


                    if (
                        valor.length > 5
                    ) {

                        valor =
                            valor.replace(
                                /^(\d{5})(\d)/,
                                '$1-$2'
                            );
                    }


                    return valor;
                }


                function limparMensagemCep() {

                    if (!cepMessage) {
                        return;
                    }


                    cepMessage.textContent =
                        '';


                    cepMessage.classList.remove(
                        'success',
                        'error'
                    );
                }


                function mostrarMensagemCep(
                    mensagem,
                    tipo
                ) {

                    if (!cepMessage) {
                        return;
                    }


                    cepMessage.textContent =
                        mensagem;


                    cepMessage.classList.remove(
                        'success',
                        'error'
                    );


                    if (tipo) {

                        cepMessage.classList.add(
                            tipo
                        );
                    }
                }


                async function consultarCep() {

                    if (
                        !cep ||
                        !addressForm
                    ) {

                        return;
                    }


                    const cepNumerico =
                        cep.value.replace(
                            /\D/g,
                            ''
                        );


                    if (
                        cepNumerico.length !== 8
                    ) {

                        ultimoCepConsultado =
                            null;

                        limparMensagemCep();

                        return;
                    }


                    /*
                     * Evita fazer duas consultas seguidas
                     * para o mesmo CEP no input + blur.
                     */

                    if (
                        cepNumerico ===
                        ultimoCepConsultado
                    ) {

                        return;
                    }


                    ultimoCepConsultado =
                        cepNumerico;


                    const baseUrl =
                        addressForm.dataset
                        .cepUrl;


                    if (!baseUrl) {
                        return;
                    }


                    limparMensagemCep();


                    if (cepLoading) {

                        cepLoading.hidden =
                            false;
                    }


                    try {

                        const response =
                            await fetch(
                                `${baseUrl}/${cepNumerico}`, {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }
                            );


                        const dados =
                            await response.json();


                        if (
                            !response.ok ||
                            dados.erro
                        ) {

                            throw new Error(
                                dados.message ??
                                'CEP não encontrado.'
                            );
                        }


                        if (logradouro) {

                            logradouro.value =
                                dados.logradouro ??
                                '';
                        }


                        if (bairro) {

                            bairro.value =
                                dados.bairro ??
                                '';
                        }


                        if (cidade) {

                            cidade.value =
                                dados.localidade ??
                                dados.cidade ??
                                '';
                        }


                        if (estado) {

                            estado.value =
                                dados.uf ??
                                dados.estado ??
                                '';
                        }


                        mostrarMensagemCep(
                            'CEP encontrado.',
                            'success'
                        );


                        if (numero) {

                            numero.focus();
                        }

                    } catch (error) {

                        ultimoCepConsultado =
                            null;


                        mostrarMensagemCep(
                            error.message ??
                            'Não foi possível consultar o CEP.',
                            'error'
                        );

                    } finally {

                        if (cepLoading) {

                            cepLoading.hidden =
                                true;
                        }
                    }
                }


                if (cep) {

                    cep.value =
                        formatarCep(
                            cep.value
                        );


                    cep.addEventListener(
                        'input',
                        function() {

                            this.value =
                                formatarCep(
                                    this.value
                                );


                            const numeros =
                                this.value.replace(
                                    /\D/g,
                                    ''
                                );


                            /*
                             * Caso usuário comece
                             * a alterar o CEP novamente.
                             */

                            if (
                                numeros !==
                                ultimoCepConsultado
                            ) {

                                ultimoCepConsultado =
                                    null;
                            }


                            if (
                                numeros.length === 8
                            ) {

                                consultarCep();
                            }
                        }
                    );


                    cep.addEventListener(
                        'blur',
                        consultarCep
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | ESTADO EM MAIÚSCULO
                |--------------------------------------------------------------------------
                */

                if (estado) {

                    estado.addEventListener(
                        'input',
                        function() {

                            this.value =
                                this.value
                                .replace(
                                    /[^a-zA-Z]/g,
                                    ''
                                )
                                .substring(
                                    0,
                                    2
                                )
                                .toUpperCase();
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | EXCLUIR CONTA
                |--------------------------------------------------------------------------
                */

                const deleteAccountForm =
                    document.getElementById(
                        'deleteAccountForm'
                    );


                if (deleteAccountForm) {

                    deleteAccountForm.addEventListener(
                        'submit',
                        function(event) {

                            const confirmado =
                                window.confirm(
                                    'Tem certeza que deseja excluir permanentemente sua conta?'
                                );


                            if (!confirmado) {

                                event.preventDefault();
                            }
                        }
                    );
                }

            }
        );
    </script>

</body>

</html>