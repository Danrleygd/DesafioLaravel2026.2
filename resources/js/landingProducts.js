document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | CARROSSEL DE PRODUTOS DA LANDING
    |--------------------------------------------------------------------------
    |
    | Cada .landing-products-wrapper funciona como um carrossel independente.
    |
    | Desktop: 5 produtos por página
    | Tablet:  3 produtos por página
    | Mobile:  2 produtos por página
    | Pequeno: 1 produto por página
    |
    */

    const carrosseis = document.querySelectorAll(
        '.landing-products-wrapper'
    );


    carrosseis.forEach(function (wrapper) {

        const container = wrapper.querySelector(
            '.landing-products'
        );

        const setaEsquerda = wrapper.querySelector(
            '.landing-arrow-left'
        );

        const setaDireita = wrapper.querySelector(
            '.landing-arrow-right'
        );


        if (
            !container ||
            !setaEsquerda ||
            !setaDireita
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO DO CARROSSEL
        |--------------------------------------------------------------------------
        */

        let paginaAtual = 0;
        let estaAnimando = false;


        /*
        |--------------------------------------------------------------------------
        | PEGAR TODOS OS CARDS
        |--------------------------------------------------------------------------
        */

        function getCards() {

            return Array.from(
                container.querySelectorAll(
                    '.landing-product-card'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | QUANTIDADE VISÍVEL
        |--------------------------------------------------------------------------
        */

        function getQuantidadeVisivel() {

            const larguraTela =
                window.innerWidth;


            if (larguraTela <= 390) {
                return 1;
            }


            if (larguraTela <= 600) {
                return 2;
            }


            if (larguraTela <= 900) {
                return 3;
            }


            return 5;
        }


        /*
        |--------------------------------------------------------------------------
        | QUANTIDADE DE PÁGINAS
        |--------------------------------------------------------------------------
        */

        function getTotalPaginas() {

            const cards =
                getCards();

            const quantidadeVisivel =
                getQuantidadeVisivel();


            return Math.max(
                1,
                Math.ceil(
                    cards.length /
                    quantidadeVisivel
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CARD INICIAL DE CADA PÁGINA
        |--------------------------------------------------------------------------
        */

        function getIndicePagina(pagina) {

            return (
                pagina *
                getQuantidadeVisivel()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLAR ATÉ UMA PÁGINA
        |--------------------------------------------------------------------------
        */

        function irParaPagina(
            pagina,
            comportamento = 'smooth'
        ) {

            const cards =
                getCards();


            if (cards.length === 0) {
                return;
            }


            const totalPaginas =
                getTotalPaginas();


            /*
             * Faz o carrossel circular.
             */

            if (pagina >= totalPaginas) {

                pagina = 0;
            }


            if (pagina < 0) {

                pagina =
                    totalPaginas - 1;
            }


            paginaAtual = pagina;


            let indice =
                getIndicePagina(
                    paginaAtual
                );


            /*
             * Se a última página não possuir
             * 5 produtos completos, ajusta para
             * mostrar os últimos produtos sem
             * deixar espaço vazio.
             */

            if (
                indice >=
                cards.length
            ) {

                indice =
                    cards.length - 1;
            }


            const cardDestino =
                cards[indice];


            if (!cardDestino) {
                return;
            }


            /*
             * offsetLeft representa a posição
             * do card dentro do próprio carrossel.
             */

            const destino =
                cardDestino.offsetLeft;


            container.scrollTo({
                left: destino,
                behavior: comportamento
            });


            atualizarSetas();
        }


        /*
        |--------------------------------------------------------------------------
        | PRÓXIMA PÁGINA
        |--------------------------------------------------------------------------
        */

        function proximaPagina() {

            if (estaAnimando) {
                return;
            }


            estaAnimando = true;


            irParaPagina(
                paginaAtual + 1
            );


            setTimeout(
                function () {

                    estaAnimando = false;

                },
                450
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PÁGINA ANTERIOR
        |--------------------------------------------------------------------------
        */

        function paginaAnterior() {

            if (estaAnimando) {
                return;
            }


            estaAnimando = true;


            irParaPagina(
                paginaAtual - 1
            );


            setTimeout(
                function () {

                    estaAnimando = false;

                },
                450
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR / ESCONDER SETAS
        |--------------------------------------------------------------------------
        */

        function atualizarSetas() {

            const cards =
                getCards();

            const quantidadeVisivel =
                getQuantidadeVisivel();


            /*
             * Se existem menos produtos que
             * a quantidade visível, não há
             * necessidade das setas.
             */

            if (
                cards.length <=
                quantidadeVisivel
            ) {

                setaEsquerda.classList.add(
                    'landing-arrow-hidden'
                );

                setaDireita.classList.add(
                    'landing-arrow-hidden'
                );

                return;
            }


            setaEsquerda.classList.remove(
                'landing-arrow-hidden'
            );

            setaDireita.classList.remove(
                'landing-arrow-hidden'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CLIQUE SETA DIREITA
        |--------------------------------------------------------------------------
        */

        setaDireita.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                proximaPagina();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLIQUE SETA ESQUERDA
        |--------------------------------------------------------------------------
        */

        setaEsquerda.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                paginaAnterior();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SCROLL MANUAL
        |--------------------------------------------------------------------------
        |
        | Caso o usuário arraste no trackpad ou celular,
        | descobrimos aproximadamente em qual página ele está.
        |
        */

        let scrollTimeout;


        container.addEventListener(
            'scroll',
            function () {

                clearTimeout(
                    scrollTimeout
                );


                scrollTimeout =
                    setTimeout(
                        function () {

                            const cards =
                                getCards();


                            if (
                                cards.length === 0
                            ) {
                                return;
                            }


                            let cardMaisProximo =
                                0;

                            let menorDistancia =
                                Infinity;


                            cards.forEach(
                                function (
                                    card,
                                    index
                                ) {

                                    const distancia =
                                        Math.abs(
                                            container.scrollLeft -
                                            card.offsetLeft
                                        );


                                    if (
                                        distancia <
                                        menorDistancia
                                    ) {

                                        menorDistancia =
                                            distancia;

                                        cardMaisProximo =
                                            index;
                                    }
                                }
                            );


                            paginaAtual =
                                Math.floor(
                                    cardMaisProximo /
                                    getQuantidadeVisivel()
                                );

                        },
                        100
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVIDADE
        |--------------------------------------------------------------------------
        */

        let resizeTimeout;


        window.addEventListener(
            'resize',
            function () {

                clearTimeout(
                    resizeTimeout
                );


                resizeTimeout =
                    setTimeout(
                        function () {

                            /*
                             * Ao mudar de desktop
                             * para mobile ou vice-versa,
                             * volta para o início.
                             */

                            paginaAtual = 0;


                            irParaPagina(
                                0,
                                'auto'
                            );


                            atualizarSetas();

                        },
                        180
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOUCH / SWIPE
        |--------------------------------------------------------------------------
        */

        let touchInicio = 0;
        let touchFim = 0;


        container.addEventListener(
            'touchstart',
            function (event) {

                touchInicio =
                    event
                        .changedTouches[0]
                        .screenX;
            },
            {
                passive: true
            }
        );


        container.addEventListener(
            'touchend',
            function (event) {

                touchFim =
                    event
                        .changedTouches[0]
                        .screenX;


                const diferenca =
                    touchInicio -
                    touchFim;


                /*
                 * Só considera swipe quando
                 * o movimento passar de 50px.
                 */

                if (
                    Math.abs(diferenca) <
                    50
                ) {
                    return;
                }


                if (diferenca > 0) {

                    proximaPagina();

                } else {

                    paginaAnterior();
                }

            },
            {
                passive: true
            }
        );


        /*
        |--------------------------------------------------------------------------
        | INICIALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        atualizarSetas();

        container.scrollLeft = 0;

    });

});