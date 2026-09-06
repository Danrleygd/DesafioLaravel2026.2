document.addEventListener(
    'DOMContentLoaded',
    function () {

        const btnNovoEndereco =
            document.getElementById(
                'btnNovoEndereco'
            );

        const btnFecharEndereco =
            document.getElementById(
                'btnFecharEndereco'
            );

        const novoEnderecoForm =
            document.getElementById(
                'novoEnderecoForm'
            );

        const radios =
            document.querySelectorAll(
                '.endereco-radio'
            );


        /*
        |--------------------------------------------------------------------------
        | ENDEREÇO SELECIONADO
        |--------------------------------------------------------------------------
        */

        function atualizarEnderecoSelecionado() {

            document
                .querySelectorAll(
                    '.endereco-item'
                )
                .forEach(
                    function (item) {

                        const radio =
                            item.querySelector(
                                '.endereco-radio'
                            );

                        item.classList.toggle(
                            'selected',
                            radio.checked
                        );
                    }
                );
        }


        radios.forEach(
            function (radio) {

                radio.addEventListener(
                    'change',
                    atualizarEnderecoSelecionado
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR NOVO ENDEREÇO
        |--------------------------------------------------------------------------
        */

        if (
            btnNovoEndereco &&
            novoEnderecoForm
        ) {

            btnNovoEndereco.addEventListener(
                'click',
                function () {

                    novoEnderecoForm
                        .classList
                        .add('show');

                    novoEnderecoForm
                        .scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR NOVO ENDEREÇO
        |--------------------------------------------------------------------------
        */

        if (
            btnFecharEndereco &&
            novoEnderecoForm
        ) {

            btnFecharEndereco.addEventListener(
                'click',
                function () {

                    novoEnderecoForm
                        .classList
                        .remove('show');

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VIA CEP
        |--------------------------------------------------------------------------
        */

        const cepInput =
            document.getElementById('cep');

        const btnBuscarCep =
            document.getElementById(
                'buscarCep'
            );

        const cepStatus =
            document.getElementById(
                'cepStatus'
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


        /*
        |--------------------------------------------------------------------------
        | MÁSCARA CEP
        |--------------------------------------------------------------------------
        */

        if (cepInput) {

            cepInput.addEventListener(
                'input',
                function () {

                    let valor =
                        cepInput.value
                            .replace(/\D/g, '')
                            .slice(0, 8);

                    if (valor.length > 5) {

                        valor =
                            valor.slice(0, 5)
                            +
                            '-'
                            +
                            valor.slice(5);
                    }

                    cepInput.value = valor;

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTAR
        |--------------------------------------------------------------------------
        */

        async function consultarCep() {

            if (!cepInput) {
                return;
            }

            const cep =
                cepInput.value
                    .replace(/\D/g, '');


            if (cep.length !== 8) {

                mostrarStatusCep(
                    'Digite um CEP válido.',
                    'error'
                );

                return;
            }


            mostrarStatusCep(
                'Buscando endereço...',
                ''
            );


            if (btnBuscarCep) {
                btnBuscarCep.disabled = true;
            }


            try {

                const response =
                    await fetch(
                        `/api/cep/${cep}`,
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'CEP não encontrado.'
                    );
                }


                const dados =
                    await response.json();


                if (dados.erro) {

                    throw new Error(
                        'CEP não encontrado.'
                    );
                }


                if (logradouro) {

                    logradouro.value =
                        dados.logradouro ?? '';
                }


                if (bairro) {

                    bairro.value =
                        dados.bairro ?? '';
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


                mostrarStatusCep(
                    'Endereço encontrado.',
                    'success'
                );


                const numero =
                    document.getElementById(
                        'numero'
                    );

                if (numero) {

                    numero.focus();
                }


            } catch (erro) {

                console.error(erro);

                mostrarStatusCep(
                    'Não foi possível localizar este CEP.',
                    'error'
                );


            } finally {

                if (btnBuscarCep) {
                    btnBuscarCep.disabled = false;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS CEP
        |--------------------------------------------------------------------------
        */

        function mostrarStatusCep(
            mensagem,
            tipo
        ) {

            if (!cepStatus) {
                return;
            }

            cepStatus.textContent =
                mensagem;

            cepStatus.className =
                'cep-status';

            if (tipo) {

                cepStatus.classList.add(
                    tipo
                );
            }
        }


        if (btnBuscarCep) {

            btnBuscarCep.addEventListener(
                'click',
                consultarCep
            );
        }


        if (cepInput) {

            cepInput.addEventListener(
                'keydown',
                function (event) {

                    if (event.key === 'Enter') {

                        event.preventDefault();

                        consultarCep();
                    }
                }
            );
        }


        atualizarEnderecoSelecionado();

    }
);