document.addEventListener(
    'DOMContentLoaded',
    function () {

        const body =
            document.body;

        const sidebar =
            document.getElementById(
                'userSidebar'
            );

        const collapseButton =
            document.getElementById(
                'userSidebarCollapse'
            );

        const mobileButton =
            document.getElementById(
                'userSidebarMobileButton'
            );

        const overlay =
            document.getElementById(
                'userSidebarOverlay'
            );


        if (!sidebar) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HABILITAR SIDEBAR
        |--------------------------------------------------------------------------
        */

        body.classList.add(
            'user-sidebar-enabled'
        );


        /*
        |--------------------------------------------------------------------------
        | ESTADO SALVO
        |--------------------------------------------------------------------------
        */

        const savedState =
            localStorage.getItem(
                'dtech-user-sidebar'
            );


        if (
            savedState === 'collapsed'
            &&
            window.innerWidth > 800
        ) {

            body.classList.add(
                'user-sidebar-collapsed'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RECOLHER
        |--------------------------------------------------------------------------
        */

        if (collapseButton) {

            collapseButton.addEventListener(
                'click',
                function () {

                    body.classList.toggle(
                        'user-sidebar-collapsed'
                    );


                    const collapsed =
                        body.classList.contains(
                            'user-sidebar-collapsed'
                        );


                    localStorage.setItem(
                        'dtech-user-sidebar',
                        collapsed
                            ? 'collapsed'
                            : 'expanded'
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        function abrirMobile() {

            body.classList.add(
                'user-sidebar-mobile-open'
            );


            document.body.style.overflow =
                'hidden';
        }


        function fecharMobile() {

            body.classList.remove(
                'user-sidebar-mobile-open'
            );


            document.body.style.overflow =
                '';
        }


        if (mobileButton) {

            mobileButton.addEventListener(
                'click',
                abrirMobile
            );
        }


        if (overlay) {

            overlay.addEventListener(
                'click',
                fecharMobile
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHA AO CLICAR EM LINK NO MOBILE
        |--------------------------------------------------------------------------
        */

        sidebar
            .querySelectorAll('a')
            .forEach(
                function (link) {

                    link.addEventListener(
                        'click',
                        function () {

                            if (
                                window.innerWidth
                                <= 800
                            ) {

                                fecharMobile();
                            }
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                ) {

                    fecharMobile();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESIZE
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth > 800
                ) {

                    fecharMobile();
                }
            }
        );

    }
);