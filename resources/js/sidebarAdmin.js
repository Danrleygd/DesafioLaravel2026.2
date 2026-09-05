document.addEventListener(
    'DOMContentLoaded',
    function () {

        const layout =
            document.getElementById(
                'adminLayout'
            );

        const sidebar =
            document.getElementById(
                'adminSidebar'
            );

        const toggle =
            document.getElementById(
                'adminSidebarToggle'
            );

        const mobileButton =
            document.getElementById(
                'adminMobileMenuButton'
            );

        const overlay =
            document.getElementById(
                'adminSidebarOverlay'
            );


        if (
            !layout ||
            !sidebar
        ) {
            return;
        }


        /* =====================================================
           CONFIGURAÇÕES
        ====================================================== */

        const BREAKPOINT_MOBILE = 800;

        const STORAGE_KEY =
            'dtech-admin-sidebar-collapsed';


        /* =====================================================
           VERIFICA MOBILE
        ====================================================== */

        function isMobile() {

            return (
                window.innerWidth <=
                BREAKPOINT_MOBILE
            );
        }


        /* =====================================================
           ESTADO SALVO
        ====================================================== */

        function carregarEstado() {

            if (isMobile()) {

                layout.classList.remove(
                    'sidebar-collapsed'
                );

                return;
            }


            const collapsed =
                localStorage.getItem(
                    STORAGE_KEY
                );


            if (collapsed === 'true') {

                layout.classList.add(
                    'sidebar-collapsed'
                );

            } else {

                layout.classList.remove(
                    'sidebar-collapsed'
                );
            }
        }


        /* =====================================================
           RECOLHER / EXPANDIR
        ====================================================== */

        function toggleDesktopSidebar() {

            const collapsed =
                layout.classList.toggle(
                    'sidebar-collapsed'
                );


            localStorage.setItem(
                STORAGE_KEY,
                collapsed
                    ? 'true'
                    : 'false'
            );
        }


        /* =====================================================
           ABRIR MOBILE
        ====================================================== */

        function abrirMobile() {

            layout.classList.add(
                'sidebar-mobile-open'
            );

            document.body.style.overflow =
                'hidden';
        }


        /* =====================================================
           FECHAR MOBILE
        ====================================================== */

        function fecharMobile() {

            layout.classList.remove(
                'sidebar-mobile-open'
            );

            document.body.style.overflow =
                '';
        }


        /* =====================================================
           BOTÃO DA SIDEBAR
        ====================================================== */

        if (toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    if (isMobile()) {

                        fecharMobile();

                        return;
                    }

                    toggleDesktopSidebar();
                }
            );
        }


        /* =====================================================
           BOTÃO MOBILE
        ====================================================== */

        if (mobileButton) {

            mobileButton.addEventListener(
                'click',
                function () {

                    abrirMobile();
                }
            );
        }


        /* =====================================================
           OVERLAY
        ====================================================== */

        if (overlay) {

            overlay.addEventListener(
                'click',
                function () {

                    fecharMobile();
                }
            );
        }


        /* =====================================================
           ESC
        ====================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                    &&
                    isMobile()
                ) {

                    fecharMobile();
                }
            }
        );


        /* =====================================================
           LINKS NO MOBILE
        ====================================================== */

        const links =
            sidebar.querySelectorAll(
                'a.admin-menu-item'
            );


        links.forEach(
            function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        if (isMobile()) {

                            fecharMobile();
                        }
                    }
                );
            }
        );


        /* =====================================================
           RESIZE
        ====================================================== */

        let resizeTimer;


        window.addEventListener(
            'resize',
            function () {

                clearTimeout(
                    resizeTimer
                );


                resizeTimer =
                    setTimeout(
                        function () {

                            if (isMobile()) {

                                layout.classList.remove(
                                    'sidebar-collapsed'
                                );

                            } else {

                                fecharMobile();

                                carregarEstado();
                            }

                        },
                        120
                    );
            }
        );


        /* =====================================================
           INICIALIZAÇÃO
        ====================================================== */

        carregarEstado();

    }
);