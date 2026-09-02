<x-guest-layout>

    <div class="forgot-page">

        <div class="forgot-container">

            <div class="forgot-image">

                <div class="forgot-image-content">

                    {{-- LOGIN ATIVO --}}
                    <div class="forgot-side-button">
                        Login
                    </div>

                    {{-- CADASTRO --}}
                    <a
                        href="{{ route('register') }}"
                        class="forgot-register-link">
                        Cadastre-se
                    </a>

                </div>

            </div>


            <div class="forgot-form-container">

                <div class="forgot-form">


                    <div class="forgot-user-icon">

                        <div class="forgot-user-head"></div>

                        <div class="forgot-user-body"></div>

                    </div>


                    <h1>Esqueceu a senha?</h1>



                    <p class="forgot-description">
                        Informe seu e-mail e enviaremos um
                        link para redefinir sua senha.
                    </p>



                    @if (session('status'))

                    <div class="forgot-status">
                        {{ session('status') }}
                    </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('password.email') }}">

                        @csrf



                        <div class="forgot-input-group">

                            <div class="forgot-input-icon">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7.5 12 13l9-5.5M4.5 5.25h15A1.5 1.5 0 0 1 21 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 17.25V6.75a1.5 1.5 0 0 1 1.5-1.5Z" />
                                </svg>

                            </div>


                            <div class="forgot-input-content">

                                <label for="email">
                                    Email
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email">

                            </div>

                        </div>



                        @error('email')

                        <div class="forgot-error">
                            {{ $message }}
                        </div>

                        @enderror




                        <div class="forgot-actions">

                            <a
                                href="{{ route('login') }}"
                                class="forgot-back">
                                Voltar para o login
                            </a>


                            <button
                                type="submit"
                                class="forgot-button">
                                Enviar link
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>