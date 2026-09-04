<footer class="footer">

    <div class="footer-conteudo">

        {{-- =========================================
        INFORMAÇÕES DA EMPRESA
        ========================================== --}}

        <div class="footer-sobre">

            <img
                src="{{ asset('assets/images/Logo.png') }}"
                alt="D-tech"
                class="footer-logo"
            >

            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Duis aute irure dolor in reprehenderit in voluptate velit
                esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.
            </p>

            

        </div>


        {{-- =========================================
        INSTITUCIONAL
        ========================================== --}}

        <div class="footer-coluna">

            <h3>Institucional</h3>

            <a href="#">Sobre Nós</a>
            <a href="#">Contato</a>
            <a href="#">Nossa ética</a>
            <a href="#">Nosso Objetivo</a>
            <a href="#">Nossa Perspectiva</a>

        </div>


        {{-- =========================================
        AJUDA E SUPORTE
        ========================================== --}}

        <div class="footer-coluna">

            <h3>Ajuda e Suporte</h3>

            <a href="#">Central de Ajuda</a>
            <a href="#">Como Comprar</a>
            <a href="#">Formas de Pagamento</a>
            <a href="#">Trocas e Devoluções</a>
            <a href="#">Entrega e Prazos</a>
            <a href="#">Garantia</a>

        </div>


        {{-- =========================================
        MINHA CONTA
        ========================================== --}}

        <div class="footer-coluna">

            <h3>Minha Conta</h3>

            <a href="{{ route('profile.edit') }}">Minha Conta</a>
            <a href="#">Meus Pedidos</a>
            <a href="#">Favoritos</a>
            <a href="#">Endereços</a>
            <a href="#">Sair</a>

        </div>


        {{-- =========================================
        CATEGORIAS
        ========================================== --}}

        <div class="footer-coluna">

            <h3>Categorias</h3>

            <a href="{{ route('produtos.index', ['categoria' => 'Smartphones']) }}">Smartphones</a>
            <a href="{{ route('produtos.index', ['categoria' => 'Computadores']) }}">Computadores</a>
            <a href="{{ route('produtos.index', ['categoria' => 'Audio']) }}">Áudio</a>
            <a href="{{ route('produtos.index', ['categoria' => 'Acessorios']) }}">Acessórios</a>
            <a href="{{ route('produtos.index', ['categoria' => 'Eletrodomesticos']) }}">Eletrodomésticos</a>
            <a href="{{ route('produtos.index') }}">Mais Categorias</a>

        </div>

    </div>


    {{-- =========================================
    BARRA INFERIOR
    ========================================== --}}

    <div class="footer-bottom">

        <div class="footer-direitos">

            <div class="footer-icone">

                <i class="bi bi-box-seam"></i>

            </div>

            <span>
                Todos os direitos reservados © D-tech 2025
            </span>

        </div>


        <div class="footer-legais">

            <a href="#">
                Política de privacidade
            </a>

            <span>|</span>

            <a href="#">
                Termos de Serviço
            </a>

            <span>|</span>

            <a href="#">
                Política de Cookies
            </a>

            <span>|</span>

            <a href="#">
                Segurança
            </a>

        </div>

    </div>

</footer>