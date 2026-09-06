document.addEventListener(
    'DOMContentLoaded',
    function () {

        const sidebar =
            document.getElementById(
                'userSidebar'
            );

        const toggle =
            document.getElementById(
                'userSidebarToggle'
            );


        if (!sidebar) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | MARCA O BODY
        |--------------------------------------------------------------------------
        */

        document.body.classList.add(
            'has-user-sidebar'
        );


        /*
        |--------------------------------------------------------------------------
        | RESTAURA O ESTADO
        |--------------------------------------------------------------------------
        */

        const collapsed =
            localStorage.getItem(
                'userSidebarCollapsed'
            )
            ===
            'true';


        if (
            collapsed
            &&
            window.innerWidth > 900
        ) {

            sidebar.classList.add(
                'collapsed'
            );

            document.body.classList.add(
                'user-sidebar-collapsed'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ABRIR / FECHAR
        |--------------------------------------------------------------------------
        */

        if (toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | MOBILE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        window.innerWidth
                        <=
                        900
                    ) {

                        sidebar.classList.toggle(
                            'mobile-open'
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DESKTOP
                    |--------------------------------------------------------------------------
                    */

                    sidebar.classList.toggle(
                        'collapsed'
                    );


                    const isCollapsed =
                        sidebar.classList.contains(
                            'collapsed'
                        );


                    document.body.classList.toggle(
                        'user-sidebar-collapsed',
                        isCollapsed
                    );


                    localStorage.setItem(
                        'userSidebarCollapsed',
                        isCollapsed
                            ? 'true'
                            : 'false'
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REDIMENSIONAMENTO
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth > 900
                ) {

                    sidebar.classList.remove(
                        'mobile-open'
                    );


                    const salvo =
                        localStorage.getItem(
                            'userSidebarCollapsed'
                        )
                        ===
                        'true';


                    sidebar.classList.toggle(
                        'collapsed',
                        salvo
                    );


                    document.body.classList.toggle(
                        'user-sidebar-collapsed',
                        salvo
                    );

                } else {

                    sidebar.classList.remove(
                        'collapsed'
                    );

                    document.body.classList.remove(
                        'user-sidebar-collapsed'
                    );
                }

            }
        );

    }
);