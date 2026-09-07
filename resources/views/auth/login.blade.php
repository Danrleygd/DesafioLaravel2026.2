<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | D-tech</title>

    @vite([
        'resources/css/app.css',
        'resources/css/login.css',
        'resources/js/app.js'
    ])
</head>

<body class="login-page">

    <main class="login-wrapper">

        <section class="login-card">

            {{-- =====================================================
                LADO ESQUERDO
            ====================================================== --}}

            <aside class="login-visual">

                <div class="login-visual-top">

                    <span>
                        D - TECH
                    </span>

                    <i></i>

                </div>


                <div class="login-visual-content">

                    <span class="login-eyebrow">
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


                <div class="login-visual-bottom">

                    <span>
                        SEMPRE COM VOCÊ
                    </span>

                    <i></i>

                </div>

            </aside>


            {{-- =====================================================
                FORMULÁRIO
            ====================================================== --}}

            <section class="login-form-side">


                <div class="login-language">

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


                <div class="login-form-container">


                    {{-- =================================================
                        LOGO
                    ================================================== --}}

                    <a
                        href="{{ url('/') }}"
                        class="login-logo"
                    >

                        <img
                            src="{{ asset('assets/images/Logo.png') }}"
                            alt="D-tech"
                        >

                    </a>


                    {{-- =================================================
                        CABEÇALHO
                    ================================================== --}}

                    <div class="login-heading">

                        <h2>
                            Login
                        </h2>

                        <p>
                            Acesse sua conta e continue no universo D-tech.
                        </p>

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}

                    @if(session('status'))

                        <div class="login-status">
                            {{ session('status') }}
                        </div>

                    @endif


                    {{-- =================================================
                        FORM
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="login-form"
                    >

                        @csrf


                        {{-- =============================================
                            EMAIL
                        ============================================== --}}

                        <div class="login-field">

                            <label for="email">
                                E-mail
                            </label>


                            <div class="login-input">

                                <span class="login-input-icon">

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
                                    autofocus
                                    required
                                >

                            </div>


                            @error('email')

                                <span class="login-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            SENHA
                        ============================================== --}}

                        <div class="login-field">

                            <label for="password">
                                Senha
                            </label>


                            <div class="login-input">

                                <span class="login-input-icon">

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
                                    autocomplete="current-password"
                                    required
                                >


                                <button
                                    type="button"
                                    class="login-password-toggle"
                                    id="togglePassword"
                                    aria-label="Mostrar senha"
                                >

                                    <svg
                                        class="eye-visible"
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


                                    <svg
                                        class="eye-hidden"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="m3 3 18 18" />

                                        <path
                                            d="M10.6 10.6a2 2 0 0 0 2.8 2.8"
                                        />

                                        <path
                                            d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a17 17 0 0 1-2.1 3.2"
                                        />

                                        <path
                                            d="M6.6 6.6C3.5 8.5 2 12 2 12s3.5 8 10 8a9.7 9.7 0 0 0 4.1-.9"
                                        />
                                    </svg>

                                </button>

                            </div>


                            @error('password')

                                <span class="login-error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =============================================
                            ESQUECEU SENHA
                        ============================================== --}}

                        @if(Route::has('password.request'))

                            <div class="login-forgot">

                                <a
                                    href="{{ route('password.request') }}"
                                >
                                    Esqueceu sua senha?
                                </a>

                            </div>

                        @endif


                        {{-- =============================================
                            ENTRAR
                        ============================================== --}}

                        <button
                            type="submit"
                            class="login-submit"
                        >

                            <span>
                                Entrar
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

                        <div class="login-divider">

                            <span></span>

                            <p>
                                Ainda não tem uma conta?
                            </p>

                            <span></span>

                        </div>


                        {{-- =============================================
                            CADASTRO
                        ============================================== --}}

                        <a
                            href="{{ route('register') }}"
                            class="login-register"
                        >
                            Cadastre-se
                        </a>

                    </form>


                    <div class="login-footer">

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
        document.addEventListener('DOMContentLoaded', function () {

            const button =
                document.getElementById('togglePassword');

            const password =
                document.getElementById('password');


            if (!button || !password) {
                return;
            }


            button.addEventListener('click', function () {

                const visible =
                    password.type === 'text';


                password.type =
                    visible
                        ? 'password'
                        : 'text';


                button.classList.toggle(
                    'active',
                    !visible
                );

            });

        });
    </script>

</body>

</html>