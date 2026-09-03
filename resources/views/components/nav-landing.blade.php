
    {{-- =========================================
         LINHA PRINCIPAL
    ========================================== --}}

    <div class="landing-navbar-top">

        {{-- LOGO --}}
        <x-application-logo class="landing-navbar-logo"/>


        {{-- PESQUISA --}}
        <form
            action="{{ route('posts') }}"
            method="GET"
            class="landing-search"
        >

            <input
                type="text"
                name="busca"
                placeholder="Pesquisar"
                value="{{ request('busca') }}"
            >

            <button
                type="submit"
                aria-label="Pesquisar"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z"
                    />
                </svg>

            </button>

        </form>


        {{-- =========================================
             ÍCONES
        ========================================== --}}

        <div class="landing-navbar-icons">

            {{-- FAVORITOS --}}
            <a
                href="#"
                class="landing-nav-icon"
                aria-label="Favoritos"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.6"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"
                    />
                </svg>

            </a>


            {{-- CARRINHO --}}
            <a
                href="#"
                class="landing-nav-icon"
                aria-label="Carrinho"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.6"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 1.9-1.4L21 8H6"
                    />

                    <circle
                        cx="9"
                        cy="20"
                        r="1"
                    />

                    <circle
                        cx="18"
                        cy="20"
                        r="1"
                    />

                </svg>

            </a>


            {{-- USUÁRIO --}}
            <a
                href="{{ auth()->check() ? route('profile.edit') : route('login') }}"
                class="landing-nav-icon"
                aria-label="Minha conta"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.6"
                    stroke="currentColor"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9.5"
                    />

                    <circle
                        cx="12"
                        cy="9"
                        r="3"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.8 19a5.7 5.7 0 0 1 10.4 0"
                    />

                </svg>

            </a>

        </div>

    </div>


    {{-- =========================================
         MENU INFERIOR
    ========================================== --}}

    <div class="landing-navbar-bottom">

        <a
            href="{{ route('landing') }}"
            class="landing-menu-link"
        >
            Home
        </a>

        <a
            href="#categorias"
            class="landing-menu-link"
        >
            Categorias
        </a>

        <a
            href="#produtos"
            class="landing-menu-link"
        >
            Produtos
        </a>

    </div>

</nav>  