document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | MENU DE AÇÕES
        |--------------------------------------------------------------------------
        */

        const actionButtons =
            document.querySelectorAll(
                '.admin-user-actions-button'
            );


        actionButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();


                        const container =
                            button.closest(
                                '.admin-user-actions'
                            );


                        document
                            .querySelectorAll(
                                '.admin-user-actions.open'
                            )
                            .forEach(
                                function (item) {

                                    if (
                                        item !== container
                                    ) {

                                        item.classList.remove(
                                            'open'
                                        );
                                    }
                                }
                            );


                        container.classList.toggle(
                            'open'
                        );
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | FECHAR MENU
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            function () {

                document
                    .querySelectorAll(
                        '.admin-user-actions.open'
                    )
                    .forEach(
                        function (item) {

                            item.classList.remove(
                                'open'
                            );
                        }
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO DE EXCLUSÃO
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.admin-delete-form'
            )
            .forEach(
                function (form) {

                    form.addEventListener(
                        'submit',
                        function (event) {

                            const button =
                                form.querySelector(
                                    '.admin-delete-user'
                                );


                            const nome =
                                button?.dataset?.userName
                                ?? 'este usuário';


                            const confirmado =
                                window.confirm(
                                    `Tem certeza que deseja excluir ${nome}?`
                                );


                            if (!confirmado) {

                                event.preventDefault();
                            }
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | PREVIEW DA FOTO
        |--------------------------------------------------------------------------
        */

        const fotoInput =
            document.getElementById(
                'foto'
            );


        const fotoPreview =
            document.getElementById(
                'photoPreviewImage'
            );


        const fotoPlaceholder =
            document.getElementById(
                'photoPlaceholder'
            );


        if (
            fotoInput
            &&
            fotoPreview
        ) {

            fotoInput.addEventListener(
                'change',
                function () {

                    const arquivo =
                        this.files[0];


                    if (!arquivo) {
                        return;
                    }


                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            fotoPreview.src =
                                event.target.result;

                            fotoPreview.hidden =
                                false;


                            if (
                                fotoPlaceholder
                            ) {

                                fotoPlaceholder.hidden =
                                    true;
                            }
                        };


                    reader.readAsDataURL(
                        arquivo
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CPF
        |--------------------------------------------------------------------------
        */

        const cpf =
            document.getElementById(
                'cpf'
            );


        function formatarCpf(
            valor
        ) {

            valor =
                valor.replace(
                    /\D/g,
                    ''
                );


            valor =
                valor.substring(
                    0,
                    11
                );


            valor =
                valor.replace(
                    /(\d{3})(\d)/,
                    '$1.$2'
                );


            valor =
                valor.replace(
                    /(\d{3})(\d)/,
                    '$1.$2'
                );


            valor =
                valor.replace(
                    /(\d{3})(\d{1,2})$/,
                    '$1-$2'
                );


            return valor;
        }


        if (cpf) {

            cpf.value =
                formatarCpf(
                    cpf.value
                );


            cpf.addEventListener(
                'input',
                function () {

                    this.value =
                        formatarCpf(
                            this.value
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TELEFONE
        |--------------------------------------------------------------------------
        */

        const telefone =
            document.getElementById(
                'telefone'
            );


        function formatarTelefone(
            valor
        ) {

            valor =
                valor.replace(
                    /\D/g,
                    ''
                )
                    .substring(
                        0,
                        11
                    );


            if (
                valor.length <= 10
            ) {

                return valor
                    .replace(
                        /^(\d{2})(\d)/g,
                        '($1) $2'
                    )
                    .replace(
                        /(\d{4})(\d)/,
                        '$1-$2'
                    );
            }


            return valor
                .replace(
                    /^(\d{2})(\d)/g,
                    '($1) $2'
                )
                .replace(
                    /(\d{5})(\d)/,
                    '$1-$2'
                );
        }


        if (telefone) {

            telefone.value =
                formatarTelefone(
                    telefone.value
                );


            telefone.addEventListener(
                'input',
                function () {

                    this.value =
                        formatarTelefone(
                            this.value
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CEP
        |--------------------------------------------------------------------------
        */

        const form =
            document.querySelector(
                '.admin-user-form'
            );


        const cep =
            document.getElementById(
                'cep'
            );


        const logradouro =
            document.getElementById(
                'logradouro'
            );


        const bairro =
            document.getElementById(
                'bairro'
            );


        const cidade =
            document.getElementById(
                'cidade'
            );


        const estado =
            document.getElementById(
                'estado'
            );


        const numero =
            document.getElementById(
                'numero'
            );


        const cepMessage =
            document.getElementById(
                'cepMessage'
            );


        const cepLoading =
            document.getElementById(
                'cepLoading'
            );


        /*
        |--------------------------------------------------------------------------
        | FORMATA CEP
        |--------------------------------------------------------------------------
        */

        function formatarCep(
            valor
        ) {

            valor =
                valor.replace(
                    /\D/g,
                    ''
                )
                    .substring(
                        0,
                        8
                    );


            if (
                valor.length > 5
            ) {

                valor =
                    valor.replace(
                        /^(\d{5})(\d)/,
                        '$1-$2'
                    );
            }


            return valor;
        }


        /*
        |--------------------------------------------------------------------------
        | LIMPA ENDEREÇO
        |--------------------------------------------------------------------------
        */

        function limparEndereco() {

            if (logradouro) {
                logradouro.value = '';
            }

            if (bairro) {
                bairro.value = '';
            }

            if (cidade) {
                cidade.value = '';
            }

            if (estado) {
                estado.value = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA VIA CEP
        |--------------------------------------------------------------------------
        */

        async function consultarCep() {

            if (
                !cep
                ||
                !form
            ) {
                return;
            }


            const cepNumerico =
                cep.value.replace(
                    /\D/g,
                    ''
                );


            if (
                cepNumerico.length !== 8
            ) {

                return;
            }


            const baseUrl =
                form.dataset.cepUrl;


            if (!baseUrl) {
                return;
            }


            if (cepMessage) {

                cepMessage.textContent =
                    '';
            }


            if (cepLoading) {

                cepLoading.hidden =
                    false;
            }


            try {

                const response =
                    await fetch(
                        `${baseUrl}/${cepNumerico}`,
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                const dados =
                    await response.json();


                if (
                    !response.ok
                    ||
                    dados.erro
                ) {

                    throw new Error(
                        dados.message
                        ??
                        'CEP não encontrado.'
                    );
                }


                if (logradouro) {

                    logradouro.value =
                        dados.logradouro
                        ?? '';
                }


                if (bairro) {

                    bairro.value =
                        dados.bairro
                        ?? '';
                }


                if (cidade) {

                    cidade.value =
                        dados.localidade
                        ??
                        dados.cidade
                        ??
                        '';
                }


                if (estado) {

                    estado.value =
                        dados.uf
                        ??
                        dados.estado
                        ??
                        '';
                }


                if (cepMessage) {

                    cepMessage.textContent =
                        'CEP encontrado.';

                    cepMessage.classList.remove(
                        'error'
                    );

                    cepMessage.classList.add(
                        'success'
                    );
                }


                /*
                 * Após o preenchimento,
                 * posiciona o usuário no número.
                 */

                if (numero) {

                    numero.focus();
                }

            } catch (error) {

                limparEndereco();


                if (cepMessage) {

                    cepMessage.textContent =
                        error.message
                        ??
                        'Não foi possível consultar o CEP.';

                    cepMessage.classList.remove(
                        'success'
                    );

                    cepMessage.classList.add(
                        'error'
                    );
                }

            } finally {

                if (cepLoading) {

                    cepLoading.hidden =
                        true;
                }
            }
        }


        if (cep) {

            cep.value =
                formatarCep(
                    cep.value
                );


            cep.addEventListener(
                'input',
                function () {

                    this.value =
                        formatarCep(
                            this.value
                        );


                    const numeros =
                        this.value.replace(
                            /\D/g,
                            ''
                        );


                    if (
                        numeros.length === 8
                    ) {

                        consultarCep();
                    }
                }
            );


            cep.addEventListener(
                'blur',
                consultarCep
            );
        }

    }
);