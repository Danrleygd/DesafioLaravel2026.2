<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cadastre-se | D-tech</title>

    @vite([
        'resources/css/app.css',
        'resources/css/cadastro.css',
        'resources/js/app.js'
    ])
</head>

<body class="cadastro-page">

    <main class="cadastro-wrapper">

        <section class="cadastro-card">

            {{-- =====================================================
                LADO ESQUERDO
            ====================================================== --}}

            <aside class="cadastro-visual">

                <div class="cadastro-visual-top">

                    <span>
                        D - TECH
                    </span>

                    <i></i>

                </div>


                <div class="cadastro-visual-content">

                    <span class="cadastro-eyebrow">
                        TECNOLOGIA QUE CONECTA PESSOAS
                    </span>


                    <h1>
                        Mais tecnologia
                        <br>

                        para o

                        <strong>
                            seu mundo
                        </strong>
                    </h1>


                    <p>
                        Qualidade, confiança e as melhores marcas
                        <br>
                        em um só lugar.
                    </p>

                </div>


                <div class="cadastro-visual-bottom">

                    <span>
                        SEMPRE COM VOCÊ
                    </span>

                    <i></i>

                </div>

            </aside>


            {{-- =====================================================
                LADO DIREITO
            ====================================================== --}}

            <section class="cadastro-form-side">

                <div class="cadastro-language">

                    <span>
                        PT
                    </span>

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m6 9 6 6 6-6" />
                    </svg>

                </div>


                <div class="cadastro-form-container">


                    {{-- =================================================
                        LOGO
                    ================================================== --}}

                    <a
                        href="{{ url('/') }}"
                        class="cadastro-logo"
                    >

                        <img
                            src="{{ asset('assets/images/Logo.png') }}"
                            alt="D-tech"
                        >

                    </a>


                    {{-- =================================================
                        CABEÇALHO
                    ================================================== --}}

                    <div class="cadastro-heading">

                        <h2>
                            Cadastre-se
                        </h2>

                        <p>
                            Crie sua conta e comece sua experiência na D-tech.
                        </p>

                    </div>


                    {{-- =================================================
                        FORMULÁRIO
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('register') }}"
                        class="cadastro-form"
                        id="registerForm"
                    >

                        @csrf


                        {{-- =============================================
                            NOME
                        ============================================== --}}

                        <div class="cadastro-field">

                            <label for="nome">
                                Nome
                            </label>


                            <div class="cadastro-input">

                                <span class="cadastro-input-icon">

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
                                        />

                                        <path
                                            d="M4 21a8 8 0 0 1 16 0"
                                        />
                                    </svg>

                                </span>


                                <input
                                    id="nome"
                                    type="text"
                                    name="nome"
                                    value="{{ old('nome', old('name')) }}"
                                    placeholder="Seu nome completo"
                                    autocomplete="name"
                                    autofocus
                                    required
                                >


                                {{-- Compatibilidade caso o controller
                                     ainda utilize o campo name --}}

                                <input
                                    type="hidden"
                                    name="name"
                                    id="nameBridge"
                                    value="{{ old('name', old('nome')) }}"
                                >

                            </div>


                            @error('nome')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror


                            @error('name')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            CPF
                        ============================================== --}}

                        <div class="cadastro-field">

                            <label for="cpf">
                                CPF
                            </label>


                            <div class="cadastro-input">

                                <span class="cadastro-input-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        />

                                        <circle
                                            cx="8"
                                            cy="10"
                                            r="2"
                                        />

                                        <path
                                            d="M5.5 15c.6-1.7 1.5-2.5 2.5-2.5s1.9.8 2.5 2.5"
                                        />

                                        <path d="M14 9h4" />
                                        <path d="M14 13h4" />

                                    </svg>

                                </span>


                                <input
                                    id="cpf"
                                    type="text"
                                    name="cpf"
                                    value="{{ old('cpf') }}"
                                    placeholder="000.000.000-00"
                                    maxlength="14"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    required
                                >

                            </div>


                            @error('cpf')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            EMAIL
                        ============================================== --}}

                        <div class="cadastro-field">

                            <label for="email">
                                E-mail
                            </label>


                            <div class="cadastro-input">

                                <span class="cadastro-input-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        />

                                        <path d="m3 7 9 6 9-6" />
                                    </svg>

                                </span>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="seuemail@exemplo.com"
                                    autocomplete="username"
                                    required
                                >

                            </div>


                            @error('email')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            TELEFONE + DATA DE NASCIMENTO
                        ============================================== --}}

                        <div class="cadastro-row">


                            {{-- TELEFONE --}}

                            <div class="cadastro-field">

                                <label for="telefone">
                                    Telefone
                                </label>


                                <div class="cadastro-input">

                                    <span class="cadastro-input-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"
                                            />
                                        </svg>

                                    </span>


                                    <input
                                        id="telefone"
                                        type="text"
                                        name="telefone"
                                        value="{{ old('telefone') }}"
                                        placeholder="(00) 00000-0000"
                                        maxlength="15"
                                        inputmode="numeric"
                                    >

                                </div>


                                @error('telefone')

                                    <span class="cadastro-error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- DATA DE NASCIMENTO --}}

                            <div class="cadastro-field">

                                <label for="data_nascimento">
                                    Data de nascimento
                                </label>


                                <div class="cadastro-input">

                                    <span class="cadastro-input-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <rect
                                                x="3"
                                                y="5"
                                                width="18"
                                                height="16"
                                                rx="2"
                                            />

                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M3 10h18" />

                                        </svg>

                                    </span>


                                    <input
                                        id="data_nascimento"
                                        type="date"
                                        name="data_nascimento"
                                        value="{{ old('data_nascimento') }}"
                                    >

                                </div>


                                @error('data_nascimento')

                                    <span class="cadastro-error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>

                        </div>


                        {{-- =============================================
                            SENHA
                        ============================================== --}}

                        <div class="cadastro-field">

                            <label for="password">
                                Senha
                            </label>


                            <div class="cadastro-input">

                                <span class="cadastro-input-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="2"
                                        />

                                        <path
                                            d="M8 10V7a4 4 0 0 1 8 0v3"
                                        />

                                    </svg>

                                </span>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Digite sua senha"
                                    autocomplete="new-password"
                                    required
                                >


                                <button
                                    type="button"
                                    class="cadastro-password-toggle"
                                    data-password="password"
                                    aria-label="Mostrar ou esconder senha"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="3"
                                        />
                                    </svg>

                                </button>


                                {{-- Compatibilidade com Usuarios.senha --}}

                                <input
                                    type="hidden"
                                    name="senha"
                                    id="senhaBridge"
                                >

                            </div>


                            @error('password')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror


                            @error('senha')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            CONFIRMAR SENHA
                        ============================================== --}}

                        <div class="cadastro-field">

                            <label for="password_confirmation">
                                Confirmar senha
                            </label>


                            <div class="cadastro-input">

                                <span class="cadastro-input-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="2"
                                        />

                                        <path
                                            d="M8 10V7a4 4 0 0 1 8 0v3"
                                        />

                                    </svg>

                                </span>


                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Confirme sua senha"
                                    autocomplete="new-password"
                                    required
                                >


                                <button
                                    type="button"
                                    class="cadastro-password-toggle"
                                    data-password="password_confirmation"
                                    aria-label="Mostrar ou esconder confirmação da senha"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="3"
                                        />
                                    </svg>

                                </button>


                                <input
                                    type="hidden"
                                    name="senha_confirmation"
                                    id="senhaConfirmationBridge"
                                >

                            </div>


                            @error('password_confirmation')

                                <span class="cadastro-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            TERMOS
                        ============================================== --}}

                        <label class="cadastro-terms">

                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                required
                            >

                            <span class="cadastro-checkbox"></span>


                            <span>

                                Li e aceito os

                                <a href="#">
                                    termos de uso
                                </a>

                                e a

                                <a href="#">
                                    política de privacidade
                                </a>

                            </span>

                        </label>


                        {{-- =============================================
                            BOTÃO CADASTRAR
                        ============================================== --}}

                        <button
                            type="submit"
                            class="cadastro-submit"
                        >

                            <span>
                                Cadastrar
                            </span>


                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M5 12h14" />
                                <path d="m13 6 6 6-6 6" />
                            </svg>

                        </button>


                        {{-- =============================================
                            DIVISOR
                        ============================================== --}}

                        <div class="cadastro-divider">

                            <span></span>

                            <p>
                                Já tem uma conta?
                            </p>

                            <span></span>

                        </div>


                        {{-- =============================================
                            LOGIN
                        ============================================== --}}

                        <a
                            href="{{ route('login') }}"
                            class="cadastro-login"
                        >
                            Entrar
                        </a>

                    </form>


                    {{-- =================================================
                        FOOTER
                    ================================================== --}}

                    <div class="cadastro-footer">

                        <span>
                            D-tech
                        </span>

                        <i></i>

                        <span>
                            Tecnologia mais perto de você
                        </span>

                    </div>

                </div>

            </section>

        </section>

    </main>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | NOME
                |--------------------------------------------------------------------------
                */

                const nome =
                    document.getElementById(
                        'nome'
                    );


                const nameBridge =
                    document.getElementById(
                        'nameBridge'
                    );


                function sincronizarNome() {

                    if (
                        nome
                        &&
                        nameBridge
                    ) {

                        nameBridge.value =
                            nome.value;

                    }

                }


                if (nome) {

                    nome.addEventListener(
                        'input',
                        sincronizarNome
                    );


                    sincronizarNome();

                }


                /*
                |--------------------------------------------------------------------------
                | SENHA
                |--------------------------------------------------------------------------
                */

                const password =
                    document.getElementById(
                        'password'
                    );


                const passwordConfirmation =
                    document.getElementById(
                        'password_confirmation'
                    );


                const senhaBridge =
                    document.getElementById(
                        'senhaBridge'
                    );


                const senhaConfirmationBridge =
                    document.getElementById(
                        'senhaConfirmationBridge'
                    );


                function sincronizarSenha() {

                    if (
                        password
                        &&
                        senhaBridge
                    ) {

                        senhaBridge.value =
                            password.value;

                    }


                    if (
                        passwordConfirmation
                        &&
                        senhaConfirmationBridge
                    ) {

                        senhaConfirmationBridge.value =
                            passwordConfirmation.value;

                    }

                }


                if (password) {

                    password.addEventListener(
                        'input',
                        sincronizarSenha
                    );

                }


                if (passwordConfirmation) {

                    passwordConfirmation.addEventListener(
                        'input',
                        sincronizarSenha
                    );

                }


                sincronizarSenha();


                /*
                |--------------------------------------------------------------------------
                | MOSTRAR / ESCONDER SENHA
                |--------------------------------------------------------------------------
                */

                const passwordButtons =
                    document.querySelectorAll(
                        '[data-password]'
                    );


                passwordButtons.forEach(
                    function (button) {

                        button.addEventListener(
                            'click',
                            function () {

                                const input =
                                    document.getElementById(
                                        button.dataset.password
                                    );


                                if (!input) {
                                    return;
                                }


                                if (
                                    input.type
                                    ===
                                    'password'
                                ) {

                                    input.type =
                                        'text';

                                    button.classList.add(
                                        'active'
                                    );

                                } else {

                                    input.type =
                                        'password';

                                    button.classList.remove(
                                        'active'
                                    );

                                }

                            }
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CPF
                |--------------------------------------------------------------------------
                |
                | VISUAL:
                | 123.456.789-00
                |
                | ENVIADO:
                | 12345678900
                |
                */

                const cpf =
                    document.getElementById(
                        'cpf'
                    );


                function formatarCpf(valor) {

                    let numeros =
                        valor.replace(
                            /\D/g,
                            ''
                        );


                    numeros =
                        numeros.substring(
                            0,
                            11
                        );


                    if (
                        numeros.length
                        <=
                        3
                    ) {

                        return numeros;

                    }


                    if (
                        numeros.length
                        <=
                        6
                    ) {

                        return numeros.replace(
                            /(\d{3})(\d+)/,
                            '$1.$2'
                        );

                    }


                    if (
                        numeros.length
                        <=
                        9
                    ) {

                        return numeros.replace(
                            /(\d{3})(\d{3})(\d+)/,
                            '$1.$2.$3'
                        );

                    }


                    return numeros.replace(
                        /(\d{3})(\d{3})(\d{3})(\d{1,2})/,
                        '$1.$2.$3-$4'
                    );

                }


                function limparCpf() {

                    if (!cpf) {
                        return;
                    }


                    cpf.value =
                        cpf.value.replace(
                            /\D/g,
                            ''
                        );

                }


                if (cpf) {

                    /*
                    |--------------------------------------------------------------------------
                    | FORMATA OLD('cpf')
                    |--------------------------------------------------------------------------
                    */

                    cpf.value =
                        formatarCpf(
                            cpf.value
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | FORMATA ENQUANTO DIGITA
                    |--------------------------------------------------------------------------
                    */

                    cpf.addEventListener(
                        'input',
                        function () {

                            cpf.value =
                                formatarCpf(
                                    cpf.value
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


                function formatarTelefone(valor) {

                    let numeros =
                        valor.replace(
                            /\D/g,
                            ''
                        );


                    numeros =
                        numeros.substring(
                            0,
                            11
                        );


                    if (
                        numeros.length
                        ===
                        0
                    ) {

                        return '';

                    }


                    if (
                        numeros.length
                        <=
                        2
                    ) {

                        return '('
                            +
                            numeros;

                    }


                    if (
                        numeros.length
                        <=
                        6
                    ) {

                        return numeros.replace(
                            /(\d{2})(\d+)/,
                            '($1) $2'
                        );

                    }


                    if (
                        numeros.length
                        <=
                        10
                    ) {

                        return numeros.replace(
                            /(\d{2})(\d{4})(\d{1,4})/,
                            '($1) $2-$3'
                        );

                    }


                    return numeros.replace(
                        /(\d{2})(\d{5})(\d{4})/,
                        '($1) $2-$3'
                    );

                }


                function limparTelefone() {

                    if (!telefone) {
                        return;
                    }


                    telefone.value =
                        telefone.value.replace(
                            /\D/g,
                            ''
                        );

                }


                if (telefone) {

                    telefone.value =
                        formatarTelefone(
                            telefone.value
                        );


                    telefone.addEventListener(
                        'input',
                        function () {

                            telefone.value =
                                formatarTelefone(
                                    telefone.value
                                );

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | FORMULÁRIO
                |--------------------------------------------------------------------------
                */

                const form =
                    document.getElementById(
                        'registerForm'
                    );


                if (form) {

                    form.addEventListener(
                        'submit',
                        function () {

                            /*
                            |--------------------------------------------------------------------------
                            | SINCRONIZA CAMPOS
                            |--------------------------------------------------------------------------
                            */

                            sincronizarNome();

                            sincronizarSenha();


                            /*
                            |--------------------------------------------------------------------------
                            | REMOVE MÁSCARA DO CPF
                            |--------------------------------------------------------------------------
                            |
                            | Laravel receberá exatamente:
                            |
                            | 12345678900
                            |
                            | Total: 11 caracteres
                            |
                            */

                            limparCpf();


                            /*
                            |--------------------------------------------------------------------------
                            | REMOVE MÁSCARA DO TELEFONE
                            |--------------------------------------------------------------------------
                            */

                            limparTelefone();

                        }
                    );

                }

            }
        );
    </script>

</body>

</html>