@php

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | FOTO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    $fotoUsuario = null;

    if ($user && $user->foto) {

        if (
            str_starts_with($user->foto, 'http://')
            ||
            str_starts_with($user->foto, 'https://')
        ) {

            $fotoUsuario = $user->foto;

        } else {

            $fotoUsuario = asset(
                'storage/' .
                ltrim($user->foto, '/')
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INICIAIS
    |--------------------------------------------------------------------------
    */

    $iniciais = 'U';

    if ($user && $user->nome) {

        $partesNome = preg_split(
            '/\s+/',
            trim($user->nome)
        );

        $iniciais = strtoupper(
            mb_substr(
                $partesNome[0] ?? 'U',
                0,
                1
            )
        );

        if (count($partesNome) > 1) {

            $iniciais .= strtoupper(
                mb_substr(
                    end($partesNome),
                    0,
                    1
                )
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PRIMEIRO NOME
    |--------------------------------------------------------------------------
    */

    $primeiroNome = $user
        ? explode(
            ' ',
            trim($user->nome)
        )[0]
        : 'Usuário';

@endphp


{{-- =========================================================
    BOTÃO MOBILE
========================================================= --}}

<button
    type="button"
    class="user-sidebar-mobile-button"
    id="userSidebarMobileButton"
    aria-label="Abrir menu"
>

    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
    >
        <path d="M4 6h16"></path>
        <path d="M4 12h16"></path>
        <path d="M4 18h16"></path>
    </svg>

</button>


{{-- =========================================================
    OVERLAY MOBILE
========================================================= --}}

<div
    class="user-sidebar-overlay"
    id="userSidebarOverlay"
></div>


{{-- =========================================================
    SIDEBAR
========================================================= --}}

<aside
    class="user-sidebar"
    id="userSidebar"
>


    {{-- =====================================================
        LOGO
    ====================================================== --}}

    <div class="user-sidebar-logo-area">

        <a
            href="{{ route('landing') }}"
            class="user-sidebar-logo"
        >

            {{-- LOGO GRANDE --}}
            <img
                src="{{ asset('assets/images/Logo.png') }}"
                alt="D-tech"
                class="user-sidebar-logo-full"
            >


            {{-- LOGO PEQUENA --}}
            <img
                src="{{ asset('assets/images/LetraSozinha.png') }}"
                alt="D-tech"
                class="user-sidebar-logo-small"
                onerror="this.src='{{ asset('assets/images/Logo.png') }}'"
            >

        </a>


        {{-- RECOLHER --}}
        <button
            type="button"
            class="user-sidebar-collapse"
            id="userSidebarCollapse"
            title="Recolher menu"
        >

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="m15 18-6-6 6-6"></path>
            </svg>

        </button>

    </div>


    {{-- =====================================================
        MENU
    ====================================================== --}}

    <nav class="user-sidebar-menu">


        {{-- =================================================
            PRINCIPAL
        ================================================== --}}

        <div class="user-sidebar-section">

            <span class="user-sidebar-section-title">
                PRINCIPAL
            </span>


            {{-- DASHBOARD --}}
            <a
                href="{{ route('dashboard') }}"
                class="
                    user-sidebar-item
                    {{ request()->routeIs('dashboard')
                        ? 'active'
                        : ''
                    }}
                "
                title="Início"
            >

                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M3 11.5 12 4l9 7.5"
                        ></path>

                        <path
                            d="M5 10v10h14V10"
                        ></path>

                        <path
                            d="M9 20v-6h6v6"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Início
                </span>

            </a>

        </div>


        {{-- =================================================
            MINHA CONTA
        ================================================== --}}

        <div class="user-sidebar-section">

            <span class="user-sidebar-section-title">
                MINHA CONTA
            </span>


            {{-- PERFIL --}}
            <a
                href="{{ route('profile.edit') }}"
                class="
                    user-sidebar-item
                    {{ request()->routeIs('profile.*')
                        ? 'active'
                        : ''
                    }}
                "
                title="Meu Perfil"
            >

                <span class="user-sidebar-icon">

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
                        ></circle>

                        <path
                            d="M4 21a8 8 0 0 1 16 0"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Meu Perfil
                </span>

            </a>


            {{-- MEUS PRODUTOS --}}
            <a
                href="{{ route('meus-produtos.index') }}"
                class="
                    user-sidebar-item
                    {{ request()->routeIs('meus-produtos.*')
                        ? 'active'
                        : ''
                    }}
                "
                title="Meus Produtos"
            >

                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M3 7l9-4 9 4-9 4-9-4Z"
                        ></path>

                        <path
                            d="M3 7v10l9 4 9-4V7"
                        ></path>

                        <path
                            d="M12 11v10"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Meus Produtos
                </span>

            </a>


            {{-- VENDAS --}}
            @if(Route::has('vendas.index'))

                <a
                    href="{{ route('vendas.index') }}"
                    class="
                        user-sidebar-item
                        {{ request()->routeIs('vendas.*')
                            ? 'active'
                            : ''
                        }}
                    "
                    title="Minhas Vendas"
                >

                    <span class="user-sidebar-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 19V9"></path>

                            <path d="M10 19V5"></path>

                            <path d="M16 19v-7"></path>

                            <path d="M22 19H2"></path>
                        </svg>

                    </span>


                    <span class="user-sidebar-text">
                        Minhas Vendas
                    </span>

                </a>

            @endif


            {{-- CARRINHO --}}
            <a
                href="{{ route('carrinho.index') }}"
                class="
                    user-sidebar-item
                    {{ request()->routeIs('carrinho.*')
                        ? 'active'
                        : ''
                    }}
                "
                title="Carrinho"
            >

                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle
                            cx="9"
                            cy="20"
                            r="1"
                        ></circle>

                        <circle
                            cx="19"
                            cy="20"
                            r="1"
                        ></circle>

                        <path
                            d="M3 4h2l2.5 11h11l2-7H7"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Carrinho
                </span>

            </a>

        </div>


        {{-- =================================================
            NAVEGAÇÃO
        ================================================== --}}

        <div class="user-sidebar-section">

            <span class="user-sidebar-section-title">
                NAVEGAÇÃO
            </span>


            {{-- LOJA --}}
            <a
                href="{{ route('landing') }}"
                class="
                    user-sidebar-item
                    {{ request()->routeIs('landing')
                        ? 'active'
                        : ''
                    }}
                "
                title="Voltar para a Loja"
            >

                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M3 9l2-5h14l2 5"
                        ></path>

                        <path
                            d="M5 13v7h14v-7"
                        ></path>

                        <path
                            d="M9 20v-5h6v5"
                        ></path>

                        <path
                            d="M3 9a3 3 0 0 0 6 0"
                        ></path>

                        <path
                            d="M9 9a3 3 0 0 0 6 0"
                        ></path>

                        <path
                            d="M15 9a3 3 0 0 0 6 0"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Voltar para a Loja
                </span>

            </a>

        </div>

    </nav>


    {{-- =====================================================
        PARTE INFERIOR
    ====================================================== --}}

    <div class="user-sidebar-bottom">


        {{-- LOGOUT --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="user-sidebar-logout-form"
        >

            @csrf


            <button
                type="submit"
                class="user-sidebar-item user-sidebar-logout"
                title="Sair"
            >

                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M10 17l5-5-5-5"
                        ></path>

                        <path
                            d="M15 12H3"
                        ></path>

                        <path
                            d="M15 3h5a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-5"
                        ></path>
                    </svg>

                </span>


                <span class="user-sidebar-text">
                    Sair
                </span>

            </button>

        </form>


        {{-- PERFIL --}}
        <a
            href="{{ route('profile.edit') }}"
            class="user-sidebar-profile"
        >

            <div class="user-sidebar-avatar">

                @if($fotoUsuario)

                    <img
                        src="{{ $fotoUsuario }}"
                        alt="{{ $user->nome }}"
                        class="user-sidebar-avatar-image"
                        onerror="
                            this.style.display='none';
                            this.nextElementSibling.style.display='flex';
                        "
                    >

                    <span
                        class="user-sidebar-avatar-fallback"
                        style="display: none;"
                    >
                        {{ $iniciais }}
                    </span>

                @else

                    <span class="user-sidebar-avatar-fallback">
                        {{ $iniciais }}
                    </span>

                @endif

            </div>


            <div class="user-sidebar-profile-info">

                <strong>
                    {{ $primeiroNome }}
                </strong>

                <span>
                    Ver meu perfil
                </span>

            </div>


            <svg
                class="user-sidebar-profile-arrow"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="m9 18 6-6-6-6"></path>
            </svg>

        </a>

    </div>

</aside>