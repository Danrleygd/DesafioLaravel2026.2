@php

    $editando =
        isset($administrador);


    $endereco =
        $editando
            ? $administrador
                ->enderecos
                ->first()
            : null;


    $fotoAtual = null;


    if (
        $editando
        &&
        $administrador->foto
    ) {

        $fotoAtual =
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

@endphp


{{-- =============================================================
    FOTO
============================================================== --}}

<section class="admin-form-section">

    <h2>
        Foto do administrador
    </h2>


    <div class="admin-photo-area">

        <div
            class="admin-photo-preview"
            id="photoPreview"
        >

            @if($fotoAtual)

                <img
                    src="{{ $fotoAtual }}"
                    alt="{{ $administrador->nome }}"
                    id="photoPreviewImage"
                >

                <span
                    id="photoPlaceholder"
                    hidden
                >
                    +
                </span>

            @else

                <span id="photoPlaceholder">
                    +
                </span>

                <img
                    src=""
                    alt=""
                    id="photoPreviewImage"
                    hidden
                >

            @endif

        </div>


        <div>

            <label
                for="foto"
                class="admin-photo-button"
            >
                {{ $editando
                    ? 'Alterar imagem'
                    : 'Selecionar imagem'
                }}
            </label>


            <input
                type="file"
                name="foto"
                id="foto"
                accept=".jpg,.jpeg,.png,.webp"
                hidden
            >


            <p>
                Foto opcional. JPG, PNG ou WEBP.
                Máximo de 2 MB.
            </p>

        </div>

    </div>

</section>


{{-- =============================================================
    DADOS
============================================================== --}}

<section class="admin-form-section">

    <h2>
        Dados pessoais
    </h2>


    <div class="admin-form-grid">

        <div class="admin-form-group admin-form-full">

            <label for="nome">
                Nome *
            </label>

            <input
                type="text"
                name="nome"
                id="nome"
                value="{{ old(
                    'nome',
                    $editando
                        ? $administrador->nome
                        : ''
                ) }}"
                required
            >

            @error('nome')
                <small class="admin-field-error">
                    {{ $message }}
                </small>
            @enderror

        </div>


        <div class="admin-form-group">

            <label for="email">
                E-mail *
            </label>

            <input
                type="email"
                name="email"
                id="email"
                value="{{ old(
                    'email',
                    $editando
                        ? $administrador->email
                        : ''
                ) }}"
                required
            >

            @error('email')
                <small class="admin-field-error">
                    {{ $message }}
                </small>
            @enderror

        </div>


        <div class="admin-form-group">

            <label for="cpf">
                CPF *
            </label>

            <input
                type="text"
                name="cpf"
                id="cpf"
                maxlength="14"
                value="{{ old(
                    'cpf',
                    $editando
                        ? $administrador->cpf
                        : ''
                ) }}"
                required
            >

            @error('cpf')
                <small class="admin-field-error">
                    {{ $message }}
                </small>
            @enderror

        </div>


        <div class="admin-form-group">

            <label for="data_nascimento">
                Data de nascimento *
            </label>

            <input
                type="date"
                name="data_nascimento"
                id="data_nascimento"
                value="{{ old(
                    'data_nascimento',
                    $editando
                    && $administrador->data_nascimento
                        ? $administrador
                            ->data_nascimento
                            ->format('Y-m-d')
                        : ''
                ) }}"
                required
            >

        </div>


        <div class="admin-form-group">

            <label for="telefone">
                Telefone *
            </label>

            <input
                type="text"
                name="telefone"
                id="telefone"
                maxlength="15"
                value="{{ old(
                    'telefone',
                    $editando
                        ? $administrador->telefone
                        : ''
                ) }}"
                required
            >

        </div>

    </div>

</section>


{{-- =============================================================
    ENDEREÇO
============================================================== --}}

<section class="admin-form-section">

    <div class="admin-address-title">

        <div>

            <h2>
                Endereço
            </h2>

            <p>
                Digite o CEP para preencher o endereço automaticamente.
            </p>

        </div>

    </div>


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label for="cep">
                CEP *
            </label>


            <div class="admin-cep-input">

                <input
                    type="text"
                    name="cep"
                    id="cep"
                    maxlength="9"
                    value="{{ old(
                        'cep',
                        $endereco?->cep
                    ) }}"
                    required
                >


                <span
                    id="cepLoading"
                    class="admin-cep-loading"
                    hidden
                >
                    Buscando...
                </span>

            </div>


            <small
                id="cepMessage"
                class="admin-cep-message"
            ></small>

        </div>


        <div class="admin-form-group">

            <label for="numero">
                Número *
            </label>

            <input
                type="text"
                name="numero"
                id="numero"
                maxlength="10"
                value="{{ old(
                    'numero',
                    $endereco?->numero
                ) }}"
                required
            >

        </div>


        <div class="admin-form-group admin-form-full">

            <label for="logradouro">
                Logradouro *
            </label>

            <input
                type="text"
                name="logradouro"
                id="logradouro"
                value="{{ old(
                    'logradouro',
                    $endereco?->logradouro
                ) }}"
                required
            >

        </div>


        <div class="admin-form-group">

            <label for="bairro">
                Bairro *
            </label>

            <input
                type="text"
                name="bairro"
                id="bairro"
                value="{{ old(
                    'bairro',
                    $endereco?->bairro
                ) }}"
                required
            >

        </div>


        <div class="admin-form-group">

            <label for="cidade">
                Cidade *
            </label>

            <input
                type="text"
                name="cidade"
                id="cidade"
                value="{{ old(
                    'cidade',
                    $endereco?->cidade
                ) }}"
                required
            >

        </div>


        <div class="admin-form-group">

            <label for="estado">
                Estado *
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
                required
            >

        </div>


        <div class="admin-form-group">

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
            >

        </div>

    </div>

</section>


{{-- =============================================================
    SENHA
============================================================== --}}

<section class="admin-form-section">

    <h2>
        {{ $editando
            ? 'Alterar senha'
            : 'Senha de acesso'
        }}
    </h2>


    @if($editando)

        <p class="admin-section-description">
            A senha atual nunca é exibida.
            Deixe os campos vazios para mantê-la.
        </p>

    @endif


    <div class="admin-form-grid">

        <div class="admin-form-group">

            <label for="senha">
                {{ $editando
                    ? 'Nova senha'
                    : 'Senha *'
                }}
            </label>

            <input
                type="password"
                name="senha"
                id="senha"
                {{ $editando ? '' : 'required' }}
            >

        </div>


        <div class="admin-form-group">

            <label for="senha_confirmation">
                Confirmar senha
            </label>

            <input
                type="password"
                name="senha_confirmation"
                id="senha_confirmation"
                {{ $editando ? '' : 'required' }}
            >

        </div>

    </div>

</section>