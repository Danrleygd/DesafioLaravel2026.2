<x-guest-layout>

    <div class="login-page">

        <div class="login-container">

            {{-- LADO ESQUERDO --}}
            <div class="login-image">

                <div class="register-image-content">

                    <a href="{{ route('login') }}" class="side-login">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="register-link">
                        Cadastre-se
                    </a>

                </div>

            </div>


            {{-- LADO DIREITO --}}
            <div class="register-form-container">

                <div class="register-form">

                    {{-- Ícone --}}
                    <div class="user-icon">
                        <div class="user-head"></div>
                        <div class="user-body"></div>
                    </div>

                    <h1>Cadastre-se</h1>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nome --}}
                        <div class="register-input-group">

                            <div class="register-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0-6.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM4 21a8 8 0 0 1 16 0H4Z"/>
                                </svg>
                            </div>

                            <div class="register-input-content">
                                <label for="name">Nome</label>

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('name')"
                            class="register-error"
                        />


                        {{-- CPF --}}
                        <div class="register-input-group">

                            <div class="register-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M4 4h16v16H4V4Zm2 3v10h12V7H6Zm2 2h3v2H8V9Zm0 3h8v2H8v-2Z"/>
                                </svg>
                            </div>

                            <div class="register-input-content">
                                <label for="cpf">CPF</label>

                                <input
                                    id="cpf"
                                    type="text"
                                    name="cpf"
                                    value="{{ old('cpf') }}"
                                    required
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('cpf')"
                            class="register-error"
                        />


                        {{-- Email --}}
                        <div class="register-input-group">

                            <div class="register-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M3 5h18v14H3V5Zm2 3v9h14V8l-7 4-7-4Zm1.5-1L12 10.5 17.5 7H6.5Z"/>
                                </svg>
                            </div>

                            <div class="register-input-content">
                                <label for="email">Email</label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="username"
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="register-error"
                        />


                        {{-- Senha --}}
                        <div class="register-input-group">

                            <div class="register-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm7 12H7v-8h10v8Z"/>
                                </svg>
                            </div>

                            <div class="register-input-content">
                                <label for="password">Senha</label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="register-error"
                        />


                        {{-- Confirmação de senha --}}
                        <div class="register-input-group">

                            <div class="register-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0 2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm7 12H7v-8h10v8Z"/>
                                </svg>
                            </div>

                            <div class="register-input-content">
                                <label for="password_confirmation">
                                    Confirmar senha
                                </label>

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>

                        </div>


                        {{-- Termos --}}
                        <div class="terms-container">

                            <input
                                id="terms"
                                type="checkbox"
                                name="terms"
                                required
                            >

                            <label for="terms">
                                Li e aceito os termos de uso e política de privacidade
                            </label>

                        </div>


                        {{-- Botão --}}
                        <div class="register-actions">

                            <button
                                type="submit"
                                class="register-button"
                            >
                                Cadastrar
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>