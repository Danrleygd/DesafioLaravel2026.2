<x-guest-layout>

    <div class="login-page">

        <div class="login-container">

            {{-- LADO ESQUERDO --}}
            <div class="login-image">

                <div class="login-image-content">

                    <a href="{{ route('login') }}" class="side-login">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="register-link">
                        Cadastre-se
                    </a>

                </div>

            </div>


            {{-- LADO DIREITO --}}
            <div class="login-form-container">

                <div class="login-form">

                    <div class="user-icon">
                        <div class="user-head"></div>
                        <div class="user-body"></div>
                    </div>

                    <h1>Login</h1>

                    <x-auth-session-status
                        class="login-status"
                        :status="session('status')"
                    />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="input-group">

                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M12 12.75A4.75 4.75 0 1 0 12 3.25a4.75 4.75 0 0 0 0 9.5Zm0-8a3.25 3.25 0 1 1 0 6.5 3.25 3.25 0 0 1 0-6.5Z"/>
                                    <path d="M3.5 20.25c0-3.45 3.8-5.75 8.5-5.75s8.5 2.3 8.5 5.75v.5h-17v-.5Z"/>
                                </svg>
                            </div>

                            <div class="input-content">
                                <label class="text-xl" for="email">Email</label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="input-error"
                        />


                        {{-- Senha --}}
                        <div class="input-group">

                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm7 12H7v-8h10v8Z"/>
                                </svg>
                            </div>

                            <div class="input-content">
                                <label for="password">Senha</label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                >
                            </div>

                        </div>

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="input-error"
                        />


                        {{-- Ações --}}
                        <div class="login-actions">

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="forgot-password"
                                >
                                    Esqueceu sua senha?
                                </a>
                            @endif

                            <button type="submit" class="login-button">
                                Entrar
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>