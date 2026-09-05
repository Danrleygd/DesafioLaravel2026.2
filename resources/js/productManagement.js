document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | ABRIR MODAL
        |--------------------------------------------------------------------------
        */

        function abrirModal(
            id
        ) {

            if (!id) {
                return;
            }


            const modal =
                document.getElementById(
                    id
                );


            if (!modal) {
                return;
            }


            modal.classList.add(
                'open'
            );


            document.body.style.overflow =
                'hidden';
        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR MODAL
        |--------------------------------------------------------------------------
        */

        function fecharModal(
            modal
        ) {

            if (!modal) {
                return;
            }


            modal.classList.remove(
                'open'
            );


            document.body.style.overflow =
                '';
        }


        /*
        |--------------------------------------------------------------------------
        | BOTÕES ABRIR
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '[data-modal-open]'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            abrirModal(
                                this.dataset.modalOpen
                            );
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | BOTÕES FECHAR
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.pm-modal-close'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            fecharModal(
                                this.closest(
                                    '.pm-modal'
                                )
                            );
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | BACKDROP
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.pm-modal-backdrop'
            )
            .forEach(
                function (backdrop) {

                    backdrop.addEventListener(
                        'click',
                        function () {

                            fecharModal(
                                this.closest(
                                    '.pm-modal'
                                )
                            );
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
                    event.key
                    !==
                    'Escape'
                ) {
                    return;
                }


                document
                    .querySelectorAll(
                        '.pm-modal.open'
                    )
                    .forEach(
                        fecharModal
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REABRIR MODAL COM ERRO DE VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        const page =
            document.querySelector(
                '.pm-page'
            );


        if (
            page
            &&
            page.dataset.openModal
        ) {

            abrirModal(
                page.dataset.openModal
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PREVIEW FOTO
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.pm-photo-input'
            )
            .forEach(
                function (input) {

                    input.addEventListener(
                        'change',
                        function () {

                            const file =
                                this.files[0];


                            if (!file) {
                                return;
                            }


                            const targetId =
                                this.dataset
                                    .previewTarget;


                            const target =
                                document
                                    .getElementById(
                                        targetId
                                    );


                            if (!target) {
                                return;
                            }


                            const reader =
                                new FileReader();


                            reader.onload =
                                function (event) {

                                    target.innerHTML =
                                        '';


                                    const image =
                                        document.createElement(
                                            'img'
                                        );


                                    image.src =
                                        event.target.result;


                                    image.alt =
                                        'Pré-visualização';


                                    target.appendChild(
                                        image
                                    );
                                };


                            reader.readAsDataURL(
                                file
                            );
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA EXCLUSÃO
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.pm-delete-form'
            )
            .forEach(
                function (form) {

                    form.addEventListener(
                        'submit',
                        function (event) {

                            const nome =
                                this.dataset
                                    .productName
                                ??
                                'este produto';


                            const confirmado =
                                window.confirm(
                                    `Tem certeza que deseja excluir "${nome}"?`
                                );


                            if (!confirmado) {

                                event.preventDefault();
                            }
                        }
                    );
                }
            );

    }
);