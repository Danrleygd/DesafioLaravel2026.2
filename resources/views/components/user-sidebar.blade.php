<aside
    class="user-sidebar"
    id="userSidebar"
>

    {{-- =========================================================
        CABEÇALHO
    ========================================================== --}}
    <div class="user-sidebar-header">

        <a
            href="{{ route('dashboard') }}"
            class="user-sidebar-logo"
        >
            <img
                src="{{ asset('assets/images/Logo.png') }}"
                alt="D-tech"
                class="user-sidebar-logo-full"
            >

            <img
                src="{{ asset('assets/images/LetraSozinha.png') }}"
                alt="D-tech"
                class="user-sidebar-logo-small"
            >
        </a>


        <button
            type="button"
            class="user-sidebar-toggle"
            id="userSidebarToggle"
            aria-label="Recolher menu"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <rect
                    x="3"
                    y="4"
                    width="18"
                    height="16"
                    rx="2"
                />

                <path d="M9 4v16" />
            </svg>
        </button>

    </div>


    {{-- =========================================================
        NAVEGAÇÃO
    ========================================================== --}}
    <nav class="user-sidebar-nav">


        {{-- =====================================================
            PRINCIPAL
        ====================================================== --}}
        <div class="user-sidebar-group">

            <span class="user-sidebar-title">
                PRINCIPAL
            </span>


            {{-- DASHBOARD --}}
            <a
                href="{{ route('dashboard') }}"
                class="
                    user-sidebar-link
                    {{
                        request()->routeIs('dashboard')
                            ? 'active'
                            : ''
                    }}
                "
                title="Dashboard"
            >
                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                        />

                        <rect
                            x="14"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                        />
                    </svg>

                </span>

                <span class="user-sidebar-text">
                    Dashboard
                </span>
            </a>

        </div>


        {{-- =====================================================
            GERENCIAMENTO
        ====================================================== --}}
        <div class="user-sidebar-group">

            <span class="user-sidebar-title">
                GERENCIAMENTO
            </span>


            {{-- PERFIL --}}
            <a
                href="{{ route('profile.edit') }}"
                class="
                    user-sidebar-link
                    {{
                        request()->routeIs('profile.*')
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

                </span>

                <span class="user-sidebar-text">
                    Meu Perfil
                </span>
            </a>


            {{-- MEUS PRODUTOS --}}
            <a
                href="{{ route('meus-produtos.index') }}"
                class="
                    user-sidebar-link
                    {{
                        request()->routeIs('meus-produtos.*')
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
                        stroke-width="2"
                    >
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
                        />

                        <polyline
                            points="3.29 7 12 12 20.71 7"
                        />

                        <line
                            x1="12"
                            y1="22"
                            x2="12"
                            y2="12"
                        />
                    </svg>

                </span>

                <span class="user-sidebar-text">
                    Meus Produtos
                </span>
            </a>

        </div>


        {{-- =====================================================
            MOVIMENTAÇÕES
        ====================================================== --}}
        <div class="user-sidebar-group">

            <span class="user-sidebar-title">
                MOVIMENTAÇÕES
            </span>


            {{-- COMPRAS --}}
            @if(
                \Illuminate\Support\Facades\Route::has(
                    'compras.index'
                )
            )

                <a
                    href="{{ route('compras.index') }}"
                    class="
                        user-sidebar-link
                        {{
                            request()->routeIs('compras.*')
                                ? 'active'
                                : ''
                        }}
                    "
                    title="Minhas Compras"
                >
                    <span class="user-sidebar-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle
                                cx="9"
                                cy="20"
                                r="1"
                            />

                            <circle
                                cx="19"
                                cy="20"
                                r="1"
                            />

                            <path
                                d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"
                            />
                        </svg>

                    </span>

                    <span class="user-sidebar-text">
                        Compras
                    </span>
                </a>

            @endif


            {{-- VENDAS --}}
            @if(
                \Illuminate\Support\Facades\Route::has(
                    'vendas.index'
                )
            )

                <a
                    href="{{ route('vendas.index') }}"
                    class="
                        user-sidebar-link
                        {{
                            request()->routeIs('vendas.*')
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
                            stroke-width="2"
                        >
                            <polyline
                                points="3 17 9 11 13 15 21 7"
                            />

                            <polyline
                                points="15 7 21 7 21 13"
                            />
                        </svg>

                    </span>

                    <span class="user-sidebar-text">
                        Vendas
                    </span>
                </a>

            @endif


            {{-- CARRINHO --}}
            <a
                href="{{ route('carrinho.index') }}"
                class="
                    user-sidebar-link
                    {{
                        request()->routeIs('carrinho.*')
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
                        stroke-width="2"
                    >
                        <circle
                            cx="9"
                            cy="20"
                            r="1"
                        />

                        <circle
                            cx="19"
                            cy="20"
                            r="1"
                        />

                        <path
                            d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"
                        />
                    </svg>

                </span>

                <span class="user-sidebar-text">
                    Carrinho
                </span>
            </a>

        </div>


        {{-- =====================================================
            LOJA
        ====================================================== --}}
        <div class="user-sidebar-group">

            <span class="user-sidebar-title">
                LOJA
            </span>


            {{-- PÁGINA INICIAL --}}
            <a
                href="{{ route('landing') }}"
                class="
                    user-sidebar-link
                    {{
                        request()->routeIs('landing')
                            ? 'active'
                            : ''
                    }}
                "
                title="Página Inicial"
            >
                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M3 9l9-7 9 7"
                        />

                        <path
                            d="M5 10v11h14V10"
                        />

                        <path
                            d="M9 21v-6h6v6"
                        />
                    </svg>

                </span>

                <span class="user-sidebar-text">
                    Página Inicial
                </span>
            </a>

        </div>

    </nav>


    {{-- =========================================================
        RODAPÉ
    ========================================================== --}}
    <div class="user-sidebar-footer">


        {{-- PERFIL --}}
        <a
            href="{{ route('profile.edit') }}"
            class="user-sidebar-link"
            title="Meu Perfil"
        >
            <span class="user-sidebar-icon">

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

            </span>

            <span class="user-sidebar-text">
                Meu Perfil
            </span>
        </a>


        {{-- SAIR --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="user-sidebar-link user-sidebar-logout"
                title="Sair"
            >
                <span class="user-sidebar-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                        />

                        <polyline
                            points="16 17 21 12 16 7"
                        />

                        <line
                            x1="21"
                            y1="12"
                            x2="9"
                            y2="12"
                        />
                    </svg>

                </span>

                <span class="user-sidebar-text">
                    Sair
                </span>
            </button>
        </form>

    </div>

</aside>