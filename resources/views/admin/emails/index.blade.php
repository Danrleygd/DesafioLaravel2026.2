<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sistema de E-mail | D-tech</title>

    @vite([
        'resources/css/app.css',
        'resources/css/adminEmail.css'
    ])
</head>

<body class="admin-email-body">

    {{-- =========================================================
        SIDEBAR ADMINISTRATIVA
    ========================================================== --}}

    @include('components.sidebar-admin')


    {{-- =========================================================
        CONTEÚDO PRINCIPAL
    ========================================================== --}}

    <main class="admin-email-main">

        <div class="admin-email-container">


            {{-- =================================================
                CABEÇALHO
            ================================================== --}}

            <header class="admin-email-header">

                <div>

                    <span class="admin-email-eyebrow">
                        COMUNICAÇÃO
                    </span>

                    <h1>
                        Sistema de E-mail
                    </h1>

                    <p>
                        Envie mensagens diretamente para usuários
                        cadastrados na plataforma.
                    </p>

                </div>


                <div class="admin-email-header-icon">

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

                </div>

            </header>


            {{-- =================================================
                MENSAGEM DE SUCESSO
            ================================================== --}}

            @if(session('success'))

                <div class="admin-email-alert success">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M20 6 9 17l-5-5" />
                    </svg>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            {{-- =================================================
                ERROS
            ================================================== --}}

            @if($errors->any())

                <div class="admin-email-alert error">

                    @foreach($errors->all() as $error)

                        <p>
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            @endif


            {{-- =================================================
                CARD
            ================================================== --}}

            <section class="admin-email-card">

                <div class="admin-email-card-header">

                    <div>

                        <h2>
                            Nova mensagem
                        </h2>

                        <p>
                            Selecione um usuário e escreva
                            a mensagem que deseja enviar.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                    CASO NÃO EXISTAM USUÁRIOS
                ================================================== --}}

                @if($usuarios->isEmpty())

                    <div class="admin-email-empty">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
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


                        <h3>
                            Nenhum usuário encontrado
                        </h3>


                        <p>
                            Cadastre um usuário comum para
                            enviar uma mensagem.
                        </p>

                    </div>

                @else


                    {{-- =================================================
                        FORMULÁRIO
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route('admin.emails.send') }}"
                        class="admin-email-form"
                        id="adminEmailForm"
                    >

                        @csrf


                        {{-- =============================================
                            DESTINATÁRIO
                        ============================================== --}}

                        <div class="admin-email-field">

                            <label for="usuario_id">
                                Destinatário
                            </label>


                            <select
                                id="usuario_id"
                                name="usuario_id"
                                required
                            >

                                <option
                                    value=""
                                    disabled
                                    {{ old('usuario_id') ? '' : 'selected' }}
                                >
                                    Selecione um usuário
                                </option>


                                @foreach($usuarios as $usuario)

                                    <option
                                        value="{{ $usuario->id }}"
                                        {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}
                                    >
                                        {{ $usuario->nome }} — {{ $usuario->email }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =============================================
                            ASSUNTO
                        ============================================== --}}

                        <div class="admin-email-field">

                            <label for="assunto">
                                Assunto
                            </label>


                            <input
                                type="text"
                                id="assunto"
                                name="assunto"
                                value="{{ old('assunto') }}"
                                maxlength="150"
                                placeholder="Digite o assunto do e-mail"
                                required
                            >

                        </div>


                        {{-- =============================================
                            CONTEÚDO
                        ============================================== --}}

                        <div class="admin-email-field">

                            <div class="admin-email-label-row">

                                <label for="conteudo">
                                    Conteúdo do e-mail
                                </label>


                                <span id="emailCharacterCounter">
                                    0 / 10000
                                </span>

                            </div>


                            <textarea
                                id="conteudo"
                                name="conteudo"
                                rows="12"
                                maxlength="10000"
                                placeholder="Digite a mensagem que será enviada ao usuário..."
                                required
                            >{{ old('conteudo') }}</textarea>

                        </div>


                        {{-- =============================================
                            AÇÕES
                        ============================================== --}}

                        <div class="admin-email-actions">


                            <a
                                href="{{ route('admin.usuarios.index') }}"
                                class="admin-email-button secondary"
                            >
                                Voltar
                            </a>


                            <button
                                type="submit"
                                class="admin-email-button primary"
                                id="sendEmailButton"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="m22 2-7 20-4-9-9-4Z"
                                    />

                                    <path
                                        d="M22 2 11 13"
                                    />
                                </svg>


                                <span id="sendEmailButtonText">
                                    Enviar e-mail
                                </span>

                            </button>

                        </div>

                    </form>

                @endif

            </section>


            {{-- =================================================
                INFORMAÇÃO
            ================================================== --}}

            <section class="admin-email-info">

                <div class="admin-email-info-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                        <path
                            d="M12 11v5"
                        />

                        <path
                            d="M12 8h.01"
                        />
                    </svg>

                </div>


                <div>

                    <strong>
                        Envio pela D-tech
                    </strong>


                    <p>
                        A mensagem será enviada utilizando
                        o servidor SMTP configurado no projeto.
                    </p>

                </div>

            </section>

        </div>

    </main>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const textarea =
                    document.getElementById(
                        'conteudo'
                    );

                const contador =
                    document.getElementById(
                        'emailCharacterCounter'
                    );

                const formulario =
                    document.getElementById(
                        'adminEmailForm'
                    );

                const botao =
                    document.getElementById(
                        'sendEmailButton'
                    );

                const textoBotao =
                    document.getElementById(
                        'sendEmailButtonText'
                    );


                /*
                |--------------------------------------------------------------------------
                | CONTADOR DE CARACTERES
                |--------------------------------------------------------------------------
                */

                function atualizarContador() {

                    if (
                        !textarea
                        ||
                        !contador
                    ) {
                        return;
                    }


                    contador.textContent =
                        textarea.value.length
                        +
                        ' / 10000';
                }


                if (textarea) {

                    atualizarContador();


                    textarea.addEventListener(
                        'input',
                        atualizarContador
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | EVITA DUPLO ENVIO
                |--------------------------------------------------------------------------
                */

                if (
                    formulario
                    &&
                    botao
                ) {

                    formulario.addEventListener(
                        'submit',
                        function () {

                            botao.disabled = true;


                            if (textoBotao) {

                                textoBotao.textContent =
                                    'Enviando...';
                            }

                        }
                    );
                }

            }
        );

    </script>

</body>

</html>