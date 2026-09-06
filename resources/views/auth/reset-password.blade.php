<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nova senha - D-tech
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
                    Crie uma nova senha.
                </h1>


                <p>
                    Escolha uma senha segura para voltar a acessar
                    sua conta D-tech.
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

                        <path
                            d="M12 20h9"
                        />

                        <path
                            d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"
                        />

                    </svg>

                </div>


                <span class="password-reset-eyebrow">
                    NOVA SENHA
                </span>


                <h2>
                    Redefinir senha
                </h2>


                <p class="password-reset-description">
                    Preencha os campos abaixo para concluir
                    a recuperação da sua conta.
                </p>


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
                    action="{{ route('password.store') }}"
                    class="password-reset-form"
                >

                    @csrf


                    <input
                        type="hidden"
                        name="token"
                        value="{{ $request->route('token') }}"
                    >


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
                                value="{{ old('email', $request->email) }}"
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>


                    <div class="password-field">

                        <label for="password">
                            Nova senha
                        </label>


                        <div class="password-input">

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


                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Digite a nova senha"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                    </div>


                    <div class="password-field">

                        <label for="password_confirmation">
                            Confirmar nova senha
                        </label>


                        <div class="password-input">

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


                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repita a nova senha"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="password-reset-button"
                    >

                        Redefinir senha

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

            </div>

        </section>

    </main>

</body>

</html>
