<x-app-layout>

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite([
    'resources/css/profile.css'
    ])

    <div class="profile-page">

        <div class="profile-container">

            {{-- =====================================================
                 CABEÇALHO
            ====================================================== --}}

            <div class="profile-header">

                <div class="profile-header-left">

                    <a
                        href="{{ route('landing') }}"
                        class="profile-back-button"
                        title="Voltar para a página inicial">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <div>
                        <h1>Meu Perfil</h1>

                        <p>
                            Gerencie suas informações pessoais e configurações.
                        </p>
                    </div>

                </div>

            </div>


            {{-- =====================================================
                 MENSAGEM DE SUCESSO
            ====================================================== --}}

            @if (session('success'))

            <div class="profile-success">

                <i class="bi bi-check-circle"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            @endif


            {{-- =====================================================
                 ERROS
            ====================================================== --}}

            @if ($errors->any())

            <div class="profile-errors">

                <div class="profile-errors-title">

                    <i class="bi bi-exclamation-circle"></i>

                    <strong>
                        Verifique os seguintes erros:
                    </strong>

                </div>

                <ul>

                    @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif


            {{-- =====================================================
                 LAYOUT PRINCIPAL
            ====================================================== --}}

            <div class="profile-layout">


                {{-- =================================================
                     SIDEBAR
                ================================================== --}}

                <aside class="profile-sidebar">


                    {{-- USUÁRIO --}}
                    <div class="profile-user">

                        <div class="profile-user-photo">

                            @if ($user->foto)

                            @php

                            $fotoPerfil =
                            str_starts_with($user->foto, 'http://')
                            || str_starts_with($user->foto, 'https://')
                            ? $user->foto
                            : asset(
                            'storage/' .
                            ltrim($user->foto, '/')
                            );

                            @endphp

                            <img
                                src="{{ $fotoPerfil }}"
                                alt="Foto de perfil de {{ $user->nome }}">

                            @else

                            <div class="profile-user-photo-placeholder">

                                <i class="bi bi-person-fill"></i>

                            </div>

                            @endif

                        </div>


                        <div class="profile-user-info">

                            <h2>
                                {{ $user->nome }}
                            </h2>

                            <span>
                                {{ $user->email }}
                            </span>

                        </div>

                    </div>


                    {{-- MENU --}}
                    <nav class="profile-menu">

                        <a
                            href="#informacoes"
                            class="profile-menu-item active">

                            <i class="bi bi-person"></i>

                            <span>
                                Informações pessoais
                            </span>

                        </a>


                        <a
                            href="#enderecos"
                            class="profile-menu-item">

                            <i class="bi bi-geo-alt"></i>

                            <span>
                                Endereços
                            </span>

                        </a>


                        <a
                            href="#cartoes"
                            class="profile-menu-item">

                            <i class="bi bi-credit-card"></i>

                            <span>
                                Cartões
                            </span>

                        </a>


                        <a
                            href="#seguranca"
                            class="profile-menu-item">

                            <i class="bi bi-shield-lock"></i>

                            <span>
                                Segurança
                            </span>

                        </a>

                    </nav>

                </aside>


                {{-- =================================================
                     CONTEÚDO
                ================================================== --}}

                <main class="profile-content">


                    {{-- =================================================
                         INFORMAÇÕES PESSOAIS
                    ================================================== --}}

                    <section
                        id="informacoes"
                        class="profile-card">

                        <div class="profile-card-header">

                            <div>

                                <h2>
                                    Informações pessoais
                                </h2>

                                <p>
                                    Atualize seus dados pessoais.
                                </p>

                            </div>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('profile.update') }}"
                            enctype="multipart/form-data">

                            @csrf

                            @method('PATCH')


                            {{-- FOTO DE PERFIL --}}
                            <div class="profile-photo-section">

                                <div class="profile-photo-wrapper">

                                    @if ($user->foto)

                                    @php

                                    $fotoPerfil =
                                    str_starts_with($user->foto, 'http://')
                                    || str_starts_with($user->foto, 'https://')
                                    ? $user->foto
                                    : asset(
                                    'storage/' .
                                    ltrim($user->foto, '/')
                                    );

                                    @endphp

                                    <img
                                        src="{{ $fotoPerfil }}"
                                        alt="Foto de perfil de {{ $user->nome }}"
                                        class="profile-photo">

                                    @else

                                    <div class="profile-photo-placeholder">

                                        <i class="bi bi-person-fill"></i>

                                    </div>

                                    @endif

                                </div>


                                <div class="profile-photo-info">

                                    <h3>
                                        Foto de perfil
                                    </h3>

                                    <p>
                                        Sua foto será exibida no seu perfil.
                                    </p>


                                    <label
                                        for="foto"
                                        class="profile-photo-button">

                                        <i class="bi bi-camera"></i>

                                        Alterar foto

                                    </label>


                                    <input
                                        type="file"
                                        id="foto"
                                        name="foto"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        hidden>


                                    @error('foto')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>

                            </div>


                            {{-- CAMPOS --}}
                            <div class="profile-form-grid">


                                {{-- NOME --}}
                                <div class="profile-field">

                                    <label for="nome">
                                        Nome
                                    </label>

                                    <input
                                        type="text"
                                        id="nome"
                                        name="nome"
                                        value="{{ old('nome', $user->nome) }}"
                                        required>

                                    @error('nome')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- E-MAIL --}}
                                <div class="profile-field">

                                    <label for="email">
                                        E-mail
                                    </label>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        required>

                                    @error('email')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- CPF --}}
                                <div class="profile-field">

                                    <label for="cpf">
                                        CPF
                                    </label>

                                    <input
                                        type="text"
                                        id="cpf"
                                        name="cpf"
                                        value="{{ old('cpf', $user->cpf) }}">

                                    @error('cpf')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- DATA DE NASCIMENTO --}}
                                <div class="profile-field">

                                    <label for="data_nascimento">
                                        Data de nascimento
                                    </label>

                                    <input
                                        type="date"
                                        id="data_nascimento"
                                        name="data_nascimento"
                                        value="{{ old(
                                            'data_nascimento',
                                            $user->data_nascimento
                                                ? $user->data_nascimento->format('Y-m-d')
                                                : ''
                                        ) }}">

                                    @error('data_nascimento')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- TELEFONE --}}
                                <div class="profile-field">

                                    <label for="telefone">
                                        Telefone
                                    </label>

                                    <input
                                        type="text"
                                        id="telefone"
                                        name="telefone"
                                        value="{{ old('telefone', $user->telefone) }}">

                                    @error('telefone')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- SALDO --}}
                                <div class="profile-field">

                                    <label for="saldo">
                                        Saldo
                                    </label>

                                    <input
                                        type="text"
                                        id="saldo"
                                        value="R$ {{ number_format($user->saldo ?? 0, 2, ',', '.') }}"
                                        readonly>

                                </div>

                            </div>


                            {{-- BOTÃO --}}
                            <div class="profile-form-actions">

                                <button
                                    type="submit"
                                    class="profile-save-button">

                                    <i class="bi bi-check-lg"></i>

                                    Salvar alterações

                                </button>

                            </div>

                        </form>

                    </section>


                    {{-- =================================================
                         RESUMO
                    ================================================== --}}

                    <section class="profile-summary-grid">


                        {{-- PEDIDOS --}}
                        <div class="profile-summary-card">

                            <div class="profile-summary-icon">

                                <i class="bi bi-bag-check"></i>

                            </div>

                            <div>

                                <strong>
                                    {{ $totalPedidos }}
                                </strong>

                                <span>
                                    Pedidos
                                </span>

                            </div>

                        </div>


                        {{-- ENDEREÇOS --}}
                        <div class="profile-summary-card">

                            <div class="profile-summary-icon">

                                <i class="bi bi-geo-alt"></i>

                            </div>

                            <div>

                                <strong>
                                    {{ $enderecos->count() }}
                                </strong>

                                <span>
                                    Endereços
                                </span>

                            </div>

                        </div>


                        {{-- CARTÕES --}}
                        <div class="profile-summary-card">

                            <div class="profile-summary-icon">

                                <i class="bi bi-credit-card"></i>

                            </div>

                            <div>

                                <strong>
                                    {{ $cartoes->count() }}
                                </strong>

                                <span>
                                    Cartões
                                </span>

                            </div>

                        </div>

                    </section>


                    {{-- =================================================
                         ENDEREÇOS
                    ================================================== --}}

                    <section
                        id="enderecos"
                        class="profile-card">

                        <div class="profile-card-header">

                            <div>

                                <h2>
                                    Endereços cadastrados
                                </h2>

                                <p>
                                    Gerencie seus endereços de entrega.
                                </p>

                            </div>

                        </div>


                        {{-- ENDEREÇOS EXISTENTES --}}
                        @if ($enderecos->count() > 0)

                        <div class="profile-address-list">

                            @foreach ($enderecos as $endereco)

                            <div class="profile-address-card">

                                <div class="profile-address-icon">

                                    <i class="bi bi-house"></i>

                                </div>


                                <div class="profile-address-info">

                                    <strong>
                                        {{ $endereco->logradouro }},
                                        {{ $endereco->numero }}
                                    </strong>


                                    @if ($endereco->complemento)

                                    <span>
                                        {{ $endereco->complemento }}
                                    </span>

                                    @endif


                                    <span>
                                        {{ $endereco->bairro }}
                                        -
                                        {{ $endereco->cidade }}/{{ $endereco->estado }}
                                    </span>


                                    <span>
                                        CEP:

                                        {{ substr($endereco->cep, 0, 5) }}-{{ substr($endereco->cep, 5) }}
                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                        @else

                        <div class="profile-empty">

                            <i class="bi bi-geo-alt"></i>

                            <p>
                                Nenhum endereço cadastrado.
                            </p>

                        </div>

                        @endif


                        {{-- =================================================
                             CADASTRAR NOVO ENDEREÇO
                        ================================================== --}}

                        <div class="profile-new-address">

                            <div class="profile-new-address-header">

                                <h3>
                                    Cadastrar novo endereço
                                </h3>

                                <p>
                                    Digite o CEP para preencher automaticamente
                                    os dados do endereço.
                                </p>

                            </div>


                            <form
                                method="POST"
                                action="{{ route('profile.address.store') }}"
                                id="form-endereco">

                                @csrf


                                <div class="profile-form-grid">


                                    {{-- CEP --}}
                                    <div class="profile-field">

                                        <label for="cep">
                                            CEP
                                        </label>

                                        <input
                                            type="text"
                                            id="cep"
                                            name="cep"
                                            placeholder="00000-000"
                                            maxlength="9"
                                            value="{{ old('cep') }}"
                                            autocomplete="postal-code"
                                            required>

                                        <span
                                            id="cep-status"
                                            class="profile-cep-status"></span>


                                        @error('cep')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- NÚMERO --}}
                                    <div class="profile-field">

                                        <label for="numero">
                                            Número
                                        </label>

                                        <input
                                            type="text"
                                            id="numero"
                                            name="numero"
                                            placeholder="Número"
                                            value="{{ old('numero') }}"
                                            required>

                                        @error('numero')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- LOGRADOURO --}}
                                    <div class="profile-field profile-field-wide">

                                        <label for="logradouro">
                                            Logradouro
                                        </label>

                                        <input
                                            type="text"
                                            id="logradouro"
                                            name="logradouro"
                                            placeholder="Rua, Avenida..."
                                            value="{{ old('logradouro') }}"
                                            autocomplete="street-address"
                                            required>

                                        @error('logradouro')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- COMPLEMENTO --}}
                                    <div class="profile-field">

                                        <label for="complemento">
                                            Complemento
                                        </label>

                                        <input
                                            type="text"
                                            id="complemento"
                                            name="complemento"
                                            placeholder="Apartamento, bloco..."
                                            value="{{ old('complemento') }}">

                                        @error('complemento')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- BAIRRO --}}
                                    <div class="profile-field">

                                        <label for="bairro">
                                            Bairro
                                        </label>

                                        <input
                                            type="text"
                                            id="bairro"
                                            name="bairro"
                                            placeholder="Bairro"
                                            value="{{ old('bairro') }}"
                                            autocomplete="address-level3"
                                            required>

                                        @error('bairro')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- CIDADE --}}
                                    <div class="profile-field">

                                        <label for="cidade">
                                            Cidade
                                        </label>

                                        <input
                                            type="text"
                                            id="cidade"
                                            name="cidade"
                                            placeholder="Cidade"
                                            value="{{ old('cidade') }}"
                                            autocomplete="address-level2"
                                            required>

                                        @error('cidade')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>


                                    {{-- ESTADO --}}
                                    <div class="profile-field">

                                        <label for="estado">
                                            Estado
                                        </label>

                                        <input
                                            type="text"
                                            id="estado"
                                            name="estado"
                                            placeholder="MG"
                                            maxlength="2"
                                            value="{{ old('estado') }}"
                                            autocomplete="address-level1"
                                            required>

                                        @error('estado')

                                        <span class="profile-field-error">
                                            {{ $message }}
                                        </span>

                                        @enderror

                                    </div>

                                </div>


                                {{-- BOTÃO --}}
                                <div class="profile-form-actions">

                                    <button
                                        type="submit"
                                        class="profile-save-button">

                                        <i class="bi bi-plus-lg"></i>

                                        Cadastrar endereço

                                    </button>

                                </div>

                            </form>

                        </div>

                    </section>


                    {{-- =================================================
                         CARTÕES
                    ================================================== --}}

                    <section
                        id="cartoes"
                        class="profile-card">

                        <div class="profile-card-header">

                            <div>

                                <h2>
                                    Cartões cadastrados
                                </h2>

                                <p>
                                    Seus cartões salvos para pagamentos.
                                </p>

                            </div>

                        </div>


                        @if ($cartoes->count() > 0)

                        <div class="profile-card-list">

                            @foreach ($cartoes as $cartao)

                            <div class="profile-payment-card">

                                <div class="profile-payment-icon">

                                    <i class="bi bi-credit-card"></i>

                                </div>

                                <div>

                                    <strong>
                                        Cartão
                                    </strong>

                                    <span>

                                        **** **** ****

                                        {{ substr($cartao->numero ?? '', -4) }}

                                    </span>

                                </div>

                            </div>

                            @endforeach

                        </div>

                        @else

                        <div class="profile-empty">

                            <i class="bi bi-credit-card"></i>

                            <p>
                                Nenhum cartão cadastrado.
                            </p>

                        </div>

                        @endif

                    </section>


                    {{-- =================================================
                         SEGURANÇA
                    ================================================== --}}

                    <section
                        id="seguranca"
                        class="profile-card">

                        <div class="profile-card-header">

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
                            method="POST"
                            action="{{ route('profile.password') }}">

                            @csrf

                            @method('PATCH')


                            <div class="profile-form-grid">


                                {{-- SENHA ATUAL --}}
                                <div class="profile-field profile-field-wide">

                                    <label for="senha_atual">
                                        Senha atual
                                    </label>

                                    <input
                                        type="password"
                                        id="senha_atual"
                                        name="senha_atual"
                                        required>

                                    @error('senha_atual')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- NOVA SENHA --}}
                                <div class="profile-field">

                                    <label for="nova_senha">
                                        Nova senha
                                    </label>

                                    <input
                                        type="password"
                                        id="nova_senha"
                                        name="nova_senha"
                                        required>

                                    @error('nova_senha')

                                    <span class="profile-field-error">
                                        {{ $message }}
                                    </span>

                                    @enderror

                                </div>


                                {{-- CONFIRMAÇÃO --}}
                                <div class="profile-field">

                                    <label for="nova_senha_confirmation">
                                        Confirmar nova senha
                                    </label>

                                    <input
                                        type="password"
                                        id="nova_senha_confirmation"
                                        name="nova_senha_confirmation"
                                        required>

                                </div>

                            </div>


                            <div class="profile-form-actions">

                                <button
                                    type="submit"
                                    class="profile-save-button">

                                    <i class="bi bi-lock"></i>

                                    Alterar senha

                                </button>

                            </div>

                        </form>

                    </section>

                </main>

            </div>

        </div>

    </div>


    {{-- =============================================================
         JAVASCRIPT - VIACEP
    ============================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cepInput = document.getElementById('cep');
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');
            const cepStatus = document.getElementById('cep-status');


            if (!cepInput) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Formatação do CEP
            |--------------------------------------------------------------------------
            */

            cepInput.addEventListener('input', function() {

                let cep = this.value.replace(/\D/g, '');

                cep = cep.substring(0, 8);

                if (cep.length > 5) {

                    this.value =
                        cep.substring(0, 5) +
                        '-' +
                        cep.substring(5);

                } else {

                    this.value = cep;

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Consulta ViaCEP
            |--------------------------------------------------------------------------
            */

            cepInput.addEventListener('blur', async function() {

                const cep = this.value.replace(/\D/g, '');


                /*
                |--------------------------------------------------------------------------
                | CEP vazio
                |--------------------------------------------------------------------------
                */

                if (cep.length === 0) {

                    cepStatus.textContent = '';

                    cepStatus.className =
                        'profile-cep-status';

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CEP inválido
                |--------------------------------------------------------------------------
                */

                if (cep.length !== 8) {

                    cepStatus.textContent =
                        'Digite um CEP válido com 8 dígitos.';

                    cepStatus.className =
                        'profile-cep-status error';

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Carregando
                |--------------------------------------------------------------------------
                */

                cepStatus.textContent =
                    'Consultando CEP...';

                cepStatus.className =
                    'profile-cep-status loading';


                try {

                    /*
                    |--------------------------------------------------------------------------
                    | URL da API
                    |--------------------------------------------------------------------------
                    */

                    const url =
                        "{{ url('/api/cep') }}/" + cep;


                    /*
                    |--------------------------------------------------------------------------
                    | Requisição
                    |--------------------------------------------------------------------------
                    */

                    const response = await fetch(url, {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json'
                        }
                    });


                    /*
                    |--------------------------------------------------------------------------
                    | JSON
                    |--------------------------------------------------------------------------
                    */

                    const dados = await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | Erro
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {

                        throw new Error(
                            dados.erro ||
                            'Não foi possível consultar o CEP.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Preenche os campos
                    |--------------------------------------------------------------------------
                    */

                    logradouroInput.value =
                        dados.logradouro || '';

                    bairroInput.value =
                        dados.bairro || '';

                    cidadeInput.value =
                        dados.localidade || '';

                    estadoInput.value =
                        dados.uf || '';


                    /*
                    |--------------------------------------------------------------------------
                    | Sucesso
                    |--------------------------------------------------------------------------
                    */

                    cepStatus.textContent =
                        'Endereço encontrado.';

                    cepStatus.className =
                        'profile-cep-status success';


                } catch (error) {

                    /*
                    |--------------------------------------------------------------------------
                    | Limpa os campos
                    |--------------------------------------------------------------------------
                    */

                    logradouroInput.value = '';
                    bairroInput.value = '';
                    cidadeInput.value = '';
                    estadoInput.value = '';


                    /*
                    |--------------------------------------------------------------------------
                    | Exibe erro
                    |--------------------------------------------------------------------------
                    */

                    cepStatus.textContent =
                        error.message ||
                        'Erro ao consultar o CEP.';

                    cepStatus.className =
                        'profile-cep-status error';

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Estado em maiúsculo
            |--------------------------------------------------------------------------
            */

            estadoInput.addEventListener('input', function() {

                this.value = this.value
                    .replace(/[^a-zA-Z]/g, '')
                    .substring(0, 2)
                    .toUpperCase();

            });

        });
    </script>

</x-app-layout>