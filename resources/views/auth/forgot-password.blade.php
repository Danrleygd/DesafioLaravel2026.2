<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Recuperar senha - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/passwordReset.css'
    ])

</head>


<body class="password-reset-page">


    <main class="password-reset-wrapper">


        <section class="password-reset-brand">

            <a
                href="{{ route('landing') }}"
                class="password-reset-logo"
            >

                <img
                    src="{{ asset('assets/images/Logo.png') }}"
                    alt="D-tech"
                >

            </a>


            <div class="brand-content">

                <span class="brand-tag">
                    D-TECH
                </span>


                <h1>
                    Recupere o acesso à sua conta.
                </h1>


                <p>
                    Informe o e-mail cadastrado para receber
                    um link seguro de redefinição de senha.
                </p>

            </div>

        </section>


        <section class="password-reset-content">


            <div class="password-reset-card">


                <div class="password-reset-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <rect
                            x="3"
                            y="11"
                            width="18"
                            height="10"
                            rx="2"
                        />

                        <path
                            d="M7 11V7a5 5 0 0 1 10 0v4"
                        />

                    </svg>

                </div>


                <span class="password-reset-eyebrow">
                    RECUPERAÇÃO DE SENHA
                </span>


                <h2>
                    Esqueceu sua senha?
                </h2>


                <p class="password-reset-description">
                    Digite o e-mail vinculado à sua conta.
                    Enviaremos as instruções para criar uma nova senha.
                </p>


                @if(session('status'))

                    <div class="password-reset-success">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                d="M20 6 9 17l-5-5"
                            />
                        </svg>


                        <span>
                            {{ session('status') }}
                        </span>

                    </div>

                @endif


                @if($errors->any())

                    <div class="password-reset-error">

                        @foreach($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                    class="password-reset-form"
                >

                    @csrf


                    <div class="password-field">

                        <label for="email">
                            E-mail
                        </label>


                        <div class="password-input">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M4 4h16v16H4z"
                                />

                                <path
                                    d="m4 7 8 6 8-6"
                                />
                            </svg>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="seuemail@exemplo.com"
                                autocomplete="email"
                                autofocus
                                required
                            >

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="password-reset-button"
                    >

                        Enviar link de redefinição

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                d="m9 18 6-6-6-6"
                            />
                        </svg>

                    </button>

                </form>


                <div class="password-reset-footer">

                    <a href="{{ route('login') }}">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                d="m15 18-6-6 6-6"
                            />
                        </svg>

                        Voltar para o login

                    </a>

                </div>

            </div>

        </section>

    </main>

</body>

</html>
