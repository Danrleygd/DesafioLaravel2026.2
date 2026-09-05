<aside
    class="admin-sidebar"
    id="adminSidebar"
    aria-label="Menu administrativo"
>

    {{-- =========================================================
        CABEÇALHO
    ========================================================== --}}

    <div class="admin-sidebar-header">

        <a
            href="{{ route('dashboard') }}"
            class="admin-sidebar-logo"
            title="D-tech"
        >

            {{-- LOGO COMPLETA --}}
            <img
                src="{{ asset('assets/images/Logo.png') }}"
                alt="D-tech"
                class="admin-logo-full"
            >

            {{-- LOGO PEQUENA --}}
            <img
                src="{{ asset('assets/images/LetraSozinha.png') }}"
                alt="D-tech"
                class="admin-logo-small"
            >

        </a>


        <button
            type="button"
            class="admin-sidebar-toggle"
            id="adminSidebarToggle"
            aria-label="Recolher menu"
            title="Recolher menu"
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
                ></rect>

                <path d="M9 4v16"></path>
            </svg>
        </button>

    </div>


    {{-- =========================================================
        CONTEÚDO
    ========================================================== --}}

    <div class="admin-sidebar-content">


        {{-- =====================================================
            PRINCIPAL
        ====================================================== --}}

        <div class="admin-menu-group">

            <p class="admin-menu-title">
                Principal
            </p>

            <nav class="admin-menu">

                <a
                    href="{{ route('dashboard') }}"
                    class="admin-menu-item
                    {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    title="Dashboard"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect
                                x="3"
                                y="3"
                                width="7"
                                height="7"
                                rx="1"
                            ></rect>

                            <rect
                                x="14"
                                y="3"
                                width="7"
                                height="7"
                                rx="1"
                            ></rect>

                            <rect
                                x="3"
                                y="14"
                                width="7"
                                height="7"
                                rx="1"
                            ></rect>

                            <rect
                                x="14"
                                y="14"
                                width="7"
                                height="7"
                                rx="1"
                            ></rect>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Dashboard
                    </span>

                </a>

            </nav>

        </div>


        {{-- =====================================================
            GERENCIAMENTO
        ====================================================== --}}

        <div class="admin-menu-group">

            <p class="admin-menu-title">
                Gerenciamento
            </p>

            <nav class="admin-menu">

                {{-- USUÁRIOS --}}

                <a
                    href="{{ url('/admin/usuarios') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/usuarios*') ? 'active' : '' }}"
                    title="Usuários"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                            ></path>

                            <circle
                                cx="9"
                                cy="7"
                                r="4"
                            ></circle>

                            <path
                                d="M22 21v-2a4 4 0 0 0-3-3.87"
                            ></path>

                            <path
                                d="M16 3.13a4 4 0 0 1 0 7.75"
                            ></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Usuários
                    </span>

                </a>


                {{-- ADMINISTRADORES --}}

                <a
                    href="{{ url('/admin/administradores') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/administradores*') ? 'active' : '' }}"
                    title="Administradores"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"
                            ></path>

                            <circle
                                cx="12"
                                cy="10"
                                r="2"
                            ></circle>

                            <path
                                d="M8.8 16a4 4 0 0 1 6.4 0"
                            ></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Administradores
                    </span>

                </a>


                {{-- PRODUTOS --}}

                <a
                    href="{{ url('/admin/produtos') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/produtos*') ? 'active' : '' }}"
                    title="Produtos"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 9l9-6 9 6"></path>

                            <path
                                d="M21 9v10l-9 3-9-3V9"
                            ></path>

                            <path d="M3 9l9 4 9-4"></path>

                            <path d="M12 13v9"></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Produtos
                    </span>

                </a>


                {{-- Pagina Inicial --}}

                <a
                    href="{{ route('landing') }}"
                    class="admin-menu-item
                    {{ request()->routeIs('landing') ? 'active' : '' }}"
                    title="Página Inicial"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect
                                x="3"
                                y="4"
                                width="18"
                                height="16"
                                rx="2"
                            ></rect>

                            <path d="M8 8h9"></path>
                            <path d="M8 12h9"></path>
                            <path d="M8 16h9"></path>

                            <circle
                                cx="5.5"
                                cy="8"
                                r=".5"
                                fill="currentColor"
                            ></circle>

                            <circle
                                cx="5.5"
                                cy="12"
                                r=".5"
                                fill="currentColor"
                            ></circle>

                            <circle
                                cx="5.5"
                                cy="16"
                                r=".5"
                                fill="currentColor"
                            ></circle>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Página Inicial
                    </span>

                </a>

            </nav>

        </div>


        {{-- =====================================================
            VENDAS
        ====================================================== --}}

        <div class="admin-menu-group">

            <p class="admin-menu-title">
                Vendas
            </p>

            <nav class="admin-menu">


                {{-- VENDAS --}}

                <a
                    href="{{ url('/admin/vendas') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/vendas*') ? 'active' : '' }}"
                    title="Vendas"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
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
                                d="M3 4h2l2.4 11.2A2 2 0 0 0 9.4 17H18a2 2 0 0 0 2-1.6L21 9H7"
                            ></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Vendas
                    </span>

                </a>


                {{-- COMPRAS --}}

                <a
                    href="{{ url('/admin/compras') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/compras*') ? 'active' : '' }}"
                    title="Compras"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            ></circle>

                            <path d="M12 7v5l3 2"></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        Compras
                    </span>

                </a>

            </nav>

        </div>


        {{-- =====================================================
            COMUNICAÇÃO
        ====================================================== --}}

        <div class="admin-menu-group">

            <p class="admin-menu-title">
                Comunicação
            </p>

            <nav class="admin-menu">

                <a
                    href="{{ url('/admin/emails') }}"
                    class="admin-menu-item
                    {{ request()->is('admin/emails*') ? 'active' : '' }}"
                    title="E-mails"
                >

                    <span class="admin-menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="2"
                            ></rect>

                            <path d="m3 7 9 6 9-6"></path>
                        </svg>

                    </span>

                    <span class="admin-menu-text">
                        E-mails
                    </span>

                </a>

            </nav>

        </div>

    </div>


    {{-- =========================================================
        RODAPÉ
    ========================================================== --}}

    <div class="admin-sidebar-footer">


        {{-- PERFIL --}}

        <a
            href="{{ route('profile.edit') }}"
            class="admin-menu-item
            {{ request()->routeIs('profile.*') ? 'active' : '' }}"
            title="Meu Perfil"
        >

            <span class="admin-menu-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
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

            <span class="admin-menu-text">
                Meu Perfil
            </span>

        </a>


        {{-- SAIR --}}

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="admin-logout-form"
        >

            @csrf

            <button
                type="submit"
                class="admin-menu-item admin-logout"
                title="Sair"
            >

                <span class="admin-menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path
                            d="M10 17l5-5-5-5"
                        ></path>

                        <path
                            d="M15 12H3"
                        ></path>

                        <path
                            d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"
                        ></path>
                    </svg>

                </span>

                <span class="admin-menu-text">
                    Sair
                </span>

            </button>

        </form>

    </div>

</aside>


{{-- =============================================================
    BOTÃO MOBILE
============================================================== --}}

<button
    type="button"
    class="admin-mobile-menu-button"
    id="adminMobileMenuButton"
    aria-label="Abrir menu"
>

    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M4 6h16"></path>
        <path d="M4 12h16"></path>
        <path d="M4 18h16"></path>
    </svg>

</button>


<div
    class="admin-sidebar-overlay"
    id="adminSidebarOverlay"
></div>