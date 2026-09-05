<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Cadastrar Produto - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/createProduct.css'
    ])

</head>


<body class="create-product-body">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    <x-nav-landing />


    {{-- =========================================================
        PÁGINA
    ========================================================== --}}

    <main class="create-product-page">

        <div class="create-product-container">


            {{-- =====================================================
                VOLTAR
            ====================================================== --}}

            <a
                href="{{ route('meus-produtos.index') }}"
                class="create-product-back"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="m15 18-6-6 6-6"
                    ></path>
                </svg>

                Voltar para meus produtos

            </a>


            {{-- =====================================================
                HEADER
            ====================================================== --}}

            <header class="create-product-header">

                <span>
                    NOVO ANÚNCIO
                </span>


                <h1>
                    Cadastrar Produto
                </h1>


                <p>
                    Adicione as informações do produto e até 5 imagens diferentes.
                </p>

            </header>


            {{-- =====================================================
                ERROS
            ====================================================== --}}

            @if($errors->any())

                <div class="create-product-alert">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        ></circle>

                        <path d="M12 8v5"></path>
                        <path d="M12 16h.01"></path>
                    </svg>


                    <div>

                        <strong>
                            Não foi possível cadastrar o produto.
                        </strong>


                        <ul>

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                FORM
            ====================================================== --}}

            <form
                action="{{ route('meus-produtos.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="create-product-card"
                id="productForm"
            >

                @csrf


                {{-- =================================================
                    FOTOS
                ================================================== --}}

                <section class="create-product-section">

                    <div class="create-product-section-title">

                        <div class="create-product-section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="16"
                                    rx="2"
                                ></rect>

                                <circle
                                    cx="9"
                                    cy="10"
                                    r="2"
                                ></circle>

                                <path
                                    d="m21 15-5-5L5 20"
                                ></path>
                            </svg>

                        </div>


                        <div>

                            <h2>
                                Fotos do produto
                            </h2>

                            <p>
                                Clique em cada espaço para adicionar uma imagem. Você pode usar até 5.
                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                        CONTADOR
                    ================================================== --}}

                    <div class="product-photo-top">

                        <span>
                            Imagens adicionadas
                        </span>


                        <strong>

                            <span id="imageCount">
                                0
                            </span>

                            / 5

                        </strong>

                    </div>


                    {{-- =================================================
                        5 SLOTS INDEPENDENTES
                    ================================================== --}}

                    <div class="product-photo-grid">


                        @for(
                            $i = 0;
                            $i < 5;
                            $i++
                        )

                            <div
                                class="product-photo-slot"
                                id="photoSlot{{ $i }}"
                            >

                                {{-- =========================================
                                    INPUT DE ARQUIVO INDEPENDENTE
                                ========================================== --}}

                                <input
                                    type="file"
                                    name="imagens[{{ $i }}]"
                                    id="imagem{{ $i }}"
                                    class="product-photo-input"
                                    data-index="{{ $i }}"
                                    accept="image/jpeg,image/png,image/webp"
                                    hidden
                                >


                                {{-- =========================================
                                    ÁREA DA IMAGEM
                                ========================================== --}}

                                <label
                                    for="imagem{{ $i }}"
                                    class="product-photo-label"
                                >

                                    {{-- VAZIO --}}
                                    <div
                                        class="product-photo-empty"
                                        id="photoEmpty{{ $i }}"
                                    >

                                        <div class="product-photo-add-icon">

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    d="M12 5v14"
                                                ></path>

                                                <path
                                                    d="M5 12h14"
                                                ></path>
                                            </svg>

                                        </div>


                                        <strong>
                                            Adicionar
                                        </strong>


                                        <span>
                                            Foto {{ $i + 1 }}
                                        </span>

                                    </div>


                                    {{-- PREVIEW --}}
                                    <img
                                        src=""
                                        alt="Pré-visualização da imagem {{ $i + 1 }}"
                                        id="photoImage{{ $i }}"
                                        class="product-photo-image"
                                        hidden
                                    >

                                </label>


                                {{-- =========================================
                                    REMOVER
                                ========================================== --}}

                                <button
                                    type="button"
                                    class="product-photo-remove"
                                    id="removePhoto{{ $i }}"
                                    data-index="{{ $i }}"
                                    aria-label="Remover imagem"
                                    title="Remover imagem"
                                    hidden
                                >
                                    ×
                                </button>


                                {{-- =========================================
                                    PRINCIPAL
                                ========================================== --}}

                                <label
                                    class="product-main-selector"
                                    id="mainSelector{{ $i }}"
                                    hidden
                                >

                                    <input
                                        type="radio"
                                        name="principal_index"
                                        value="{{ $i }}"
                                        class="product-main-radio"
                                    >


                                    <span>
                                        Usar como principal
                                    </span>

                                </label>

                            </div>

                        @endfor


                    </div>


                    {{-- =================================================
                        INFORMAÇÕES
                    ================================================== --}}

                    <div class="product-photo-help">

                        <div>

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                ></circle>

                                <path
                                    d="M12 11v5"
                                ></path>

                                <path
                                    d="M12 8h.01"
                                ></path>
                            </svg>


                            <span>
                                A foto marcada como principal será exibida nos cards da loja.
                            </span>

                        </div>


                        <span>
                            JPG, PNG ou WEBP • máximo de 4 MB por imagem
                        </span>

                    </div>


                    @error('imagens')

                        <small class="create-product-field-error">
                            {{ $message }}
                        </small>

                    @enderror


                    @error('imagens.*')

                        <small class="create-product-field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </section>


                {{-- =================================================
                    INFORMAÇÕES DO PRODUTO
                ================================================== --}}

                <section class="create-product-section">

                    <div class="create-product-section-title">

                        <div class="create-product-section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M3 7l9-4 9 4-9 4-9-4Z"
                                ></path>

                                <path
                                    d="M3 7v10l9 4 9-4V7"
                                ></path>

                                <path
                                    d="M12 11v10"
                                ></path>
                            </svg>

                        </div>


                        <div>

                            <h2>
                                Informações do produto
                            </h2>

                            <p>
                                Esses dados serão exibidos aos compradores.
                            </p>

                        </div>

                    </div>


                    <div class="create-product-form-grid">


                        {{-- =============================================
                            NOME
                        ============================================== --}}

                        <div class="create-product-group create-product-full">

                            <label for="nome">

                                Nome do produto

                                <span>
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="nome"
                                id="nome"
                                maxlength="150"
                                value="{{ old('nome') }}"
                                placeholder="Ex.: Mouse Gamer Logitech G203"
                                required
                            >


                            @error('nome')

                                <small class="create-product-field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- =============================================
                            CATEGORIA
                        ============================================== --}}

                        <div class="create-product-group create-product-full">

                            <label for="categoria_id">

                                Categoria

                                <span>
                                    *
                                </span>

                            </label>


                            <select
                                name="categoria_id"
                                id="categoria_id"
                                required
                            >

                                <option value="">
                                    Selecione uma categoria
                                </option>


                                @foreach($categorias as $categoria)

                                    <option
                                        value="{{ $categoria->id }}"
                                        @selected(
                                            old('categoria_id')
                                            ==
                                            $categoria->id
                                        )
                                    >
                                        {{ $categoria->nome }}
                                    </option>

                                @endforeach

                            </select>


                            @error('categoria_id')

                                <small class="create-product-field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- =============================================
                            PREÇO
                        ============================================== --}}

                        <div class="create-product-group">

                            <label for="preco">

                                Preço

                                <span>
                                    *
                                </span>

                            </label>


                            <div class="create-product-price-input">

                                <span>
                                    R$
                                </span>


                                <input
                                    type="number"
                                    name="preco"
                                    id="preco"
                                    min="0.01"
                                    step="0.01"
                                    value="{{ old('preco') }}"
                                    placeholder="0,00"
                                    required
                                >

                            </div>


                            @error('preco')

                                <small class="create-product-field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- =============================================
                            QUANTIDADE
                        ============================================== --}}

                        <div class="create-product-group">

                            <label for="quantidade">

                                Quantidade

                                <span>
                                    *
                                </span>

                            </label>


                            <input
                                type="number"
                                name="quantidade"
                                id="quantidade"
                                min="0"
                                step="1"
                                value="{{ old('quantidade', 1) }}"
                                required
                            >


                            @error('quantidade')

                                <small class="create-product-field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- =============================================
                            DESCRIÇÃO
                        ============================================== --}}

                        <div class="create-product-group create-product-full">

                            <div class="create-product-description-label">

                                <label for="descricao">

                                    Descrição

                                    <span>
                                        *
                                    </span>

                                </label>


                                <small>

                                    <span id="descriptionCount">
                                        0
                                    </span>

                                    / 2000

                                </small>

                            </div>


                            <textarea
                                name="descricao"
                                id="descricao"
                                rows="7"
                                maxlength="2000"
                                placeholder="Informe as características, estado e outros detalhes do produto..."
                                required
                            >{{ old('descricao') }}</textarea>


                            @error('descricao')

                                <small class="create-product-field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>

                    </div>

                </section>


                {{-- =================================================
                    VENDEDOR
                ================================================== --}}

                <section class="create-product-seller">

                    <div class="create-product-seller-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
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

                    </div>


                    <div>

                        <span>
                            Anunciado por
                        </span>


                        <strong>
                            {{ auth()->user()->nome }}
                        </strong>


                        <small>
                            {{ auth()->user()->email }}
                        </small>

                    </div>

                </section>


                {{-- =================================================
                    FOOTER
                ================================================== --}}

                <footer class="create-product-footer">

                    <a
                        href="{{ route('meus-produtos.index') }}"
                        class="create-product-cancel"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="create-product-submit"
                        id="submitProduct"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                d="M12 5v14"
                            ></path>

                            <path
                                d="M5 12h14"
                            ></path>
                        </svg>

                        Cadastrar Produto

                    </button>

                </footer>

            </form>

        </div>

    </main>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | CONFIGURAÇÕES
                |--------------------------------------------------------------------------
                */

                const MAX_IMAGES =
                    5;


                const MAX_SIZE =
                    4 * 1024 * 1024;


                const ALLOWED_TYPES = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                /*
                |--------------------------------------------------------------------------
                | ELEMENTOS
                |--------------------------------------------------------------------------
                */

                const inputs =
                    document.querySelectorAll(
                        '.product-photo-input'
                    );


                const removeButtons =
                    document.querySelectorAll(
                        '.product-photo-remove'
                    );


                const radios =
                    document.querySelectorAll(
                        '.product-main-radio'
                    );


                const imageCount =
                    document.getElementById(
                        'imageCount'
                    );


                const productForm =
                    document.getElementById(
                        'productForm'
                    );


                const submitButton =
                    document.getElementById(
                        'submitProduct'
                    );


                const descricao =
                    document.getElementById(
                        'descricao'
                    );


                const descriptionCount =
                    document.getElementById(
                        'descriptionCount'
                    );


                /*
                |--------------------------------------------------------------------------
                | VERIFICAR SE SLOT TEM IMAGEM
                |--------------------------------------------------------------------------
                */

                function slotTemImagem(
                    index
                ) {

                    const input =
                        document.getElementById(
                            `imagem${index}`
                        );


                    return Boolean(
                        input
                        &&
                        input.files
                        &&
                        input.files.length > 0
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CONTAR IMAGENS
                |--------------------------------------------------------------------------
                */

                function contarImagens() {

                    let total =
                        0;


                    inputs.forEach(
                        function (input) {

                            if (
                                input.files
                                &&
                                input.files.length > 0
                            ) {

                                total++;
                            }
                        }
                    );


                    imageCount.textContent =
                        total;


                    return total;
                }


                /*
                |--------------------------------------------------------------------------
                | ATUALIZAR APARÊNCIA DOS RADIOS
                |--------------------------------------------------------------------------
                */

                function atualizarPrincipal() {

                    document
                        .querySelectorAll(
                            '.product-photo-slot'
                        )
                        .forEach(
                            function (slot) {

                                slot.classList.remove(
                                    'is-main'
                                );
                            }
                        );


                    radios.forEach(
                        function (radio) {

                            const selector =
                                radio.closest(
                                    '.product-main-selector'
                                );


                            if (!selector) {
                                return;
                            }


                            const text =
                                selector.querySelector(
                                    'span'
                                );


                            if (radio.checked) {

                                const slot =
                                    radio.closest(
                                        '.product-photo-slot'
                                    );


                                if (slot) {

                                    slot.classList.add(
                                        'is-main'
                                    );
                                }


                                if (text) {

                                    text.textContent =
                                        'Imagem principal';
                                }

                            } else {

                                if (text) {

                                    text.textContent =
                                        'Usar como principal';
                                }
                            }
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | GARANTIR QUE EXISTE PRINCIPAL
                |--------------------------------------------------------------------------
                */

                function garantirPrincipal() {

                    const selecionado =
                        document.querySelector(
                            '.product-main-radio:checked'
                        );


                    /*
                     * Principal atual continua válida.
                     */

                    if (
                        selecionado
                        &&
                        slotTemImagem(
                            parseInt(
                                selecionado.value
                            )
                        )
                    ) {

                        atualizarPrincipal();

                        return;
                    }


                    /*
                     * Busca primeira imagem existente.
                     */

                    for (
                        let index = 0;
                        index < MAX_IMAGES;
                        index++
                    ) {

                        if (
                            slotTemImagem(index)
                        ) {

                            const radio =
                                document.querySelector(
                                    `.product-main-radio[value="${index}"]`
                                );


                            if (radio) {

                                radio.checked =
                                    true;
                            }


                            atualizarPrincipal();

                            return;
                        }
                    }


                    /*
                     * Nenhuma imagem.
                     */

                    radios.forEach(
                        function (radio) {

                            radio.checked =
                                false;
                        }
                    );


                    atualizarPrincipal();
                }


                /*
                |--------------------------------------------------------------------------
                | LIMPAR SLOT
                |--------------------------------------------------------------------------
                */

                function limparSlot(
                    index
                ) {

                    const input =
                        document.getElementById(
                            `imagem${index}`
                        );


                    const image =
                        document.getElementById(
                            `photoImage${index}`
                        );


                    const empty =
                        document.getElementById(
                            `photoEmpty${index}`
                        );


                    const remove =
                        document.getElementById(
                            `removePhoto${index}`
                        );


                    const selector =
                        document.getElementById(
                            `mainSelector${index}`
                        );


                    const radio =
                        document.querySelector(
                            `.product-main-radio[value="${index}"]`
                        );


                    if (input) {

                        input.value =
                            '';
                    }


                    if (image) {

                        image.src =
                            '';

                        image.hidden =
                            true;
                    }


                    if (empty) {

                        empty.hidden =
                            false;
                    }


                    if (remove) {

                        remove.hidden =
                            true;
                    }


                    if (selector) {

                        selector.hidden =
                            true;
                    }


                    if (radio) {

                        radio.checked =
                            false;
                    }


                    contarImagens();

                    garantirPrincipal();
                }


                /*
                |--------------------------------------------------------------------------
                | QUANDO SELECIONAR UMA FOTO
                |--------------------------------------------------------------------------
                */

                inputs.forEach(
                    function (input) {

                        input.addEventListener(
                            'change',
                            function () {

                                const index =
                                    Number(
                                        this.dataset.index
                                    );


                                const file =
                                    this.files
                                    &&
                                    this.files.length > 0
                                        ? this.files[0]
                                        : null;


                                if (!file) {

                                    limparSlot(
                                        index
                                    );

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | VALIDAR TIPO
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    !ALLOWED_TYPES.includes(
                                        file.type
                                    )
                                ) {

                                    alert(
                                        'Selecione uma imagem JPG, PNG ou WEBP.'
                                    );


                                    limparSlot(
                                        index
                                    );


                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | VALIDAR TAMANHO
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    file.size >
                                    MAX_SIZE
                                ) {

                                    alert(
                                        'Cada imagem pode possuir no máximo 4 MB.'
                                    );


                                    limparSlot(
                                        index
                                    );


                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | ELEMENTOS DO SLOT
                                |--------------------------------------------------------------------------
                                */

                                const image =
                                    document.getElementById(
                                        `photoImage${index}`
                                    );


                                const empty =
                                    document.getElementById(
                                        `photoEmpty${index}`
                                    );


                                const remove =
                                    document.getElementById(
                                        `removePhoto${index}`
                                    );


                                const selector =
                                    document.getElementById(
                                        `mainSelector${index}`
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | PREVIEW
                                |--------------------------------------------------------------------------
                                */

                                const reader =
                                    new FileReader();


                                reader.onload =
                                    function (event) {

                                        image.src =
                                            event.target.result;


                                        image.hidden =
                                            false;


                                        empty.hidden =
                                            true;


                                        remove.hidden =
                                            false;


                                        selector.hidden =
                                            false;


                                        /*
                                         * Se for a primeira imagem,
                                         * vira principal.
                                         */

                                        const total =
                                            contarImagens();


                                        if (
                                            total === 1
                                        ) {

                                            const radio =
                                                document.querySelector(
                                                    `.product-main-radio[value="${index}"]`
                                                );


                                            if (radio) {

                                                radio.checked =
                                                    true;
                                            }
                                        }


                                        garantirPrincipal();
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
                | REMOVER
                |--------------------------------------------------------------------------
                */

                removeButtons.forEach(
                    function (button) {

                        button.addEventListener(
                            'click',
                            function (event) {

                                event.preventDefault();

                                event.stopPropagation();


                                const index =
                                    Number(
                                        this.dataset.index
                                    );


                                limparSlot(
                                    index
                                );
                            }
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | MUDAR PRINCIPAL
                |--------------------------------------------------------------------------
                */

                radios.forEach(
                    function (radio) {

                        radio.addEventListener(
                            'change',
                            function () {

                                const index =
                                    Number(
                                        this.value
                                    );


                                /*
                                 * Só permite marcar como principal
                                 * se existir imagem naquele slot.
                                 */

                                if (
                                    !slotTemImagem(index)
                                ) {

                                    this.checked =
                                        false;


                                    garantirPrincipal();

                                    return;
                                }


                                atualizarPrincipal();
                            }
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CONTADOR DE DESCRIÇÃO
                |--------------------------------------------------------------------------
                */

                function atualizarContadorDescricao() {

                    if (
                        !descricao
                        ||
                        !descriptionCount
                    ) {
                        return;
                    }


                    descriptionCount.textContent =
                        descricao.value.length;
                }


                if (descricao) {

                    atualizarContadorDescricao();


                    descricao.addEventListener(
                        'input',
                        atualizarContadorDescricao
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | SUBMIT
                |--------------------------------------------------------------------------
                */

                if (productForm) {

                    productForm.addEventListener(
                        'submit',
                        function (event) {

                            const total =
                                contarImagens();


                            /*
                             * Pelo menos uma foto.
                             */

                            if (
                                total === 0
                            ) {

                                event.preventDefault();


                                alert(
                                    'Adicione pelo menos uma imagem ao produto.'
                                );


                                return;
                            }


                            garantirPrincipal();


                            const principal =
                                document.querySelector(
                                    '.product-main-radio:checked'
                                );


                            /*
                             * Garantia adicional.
                             */

                            if (!principal) {

                                event.preventDefault();


                                alert(
                                    'Escolha a imagem principal do produto.'
                                );


                                return;
                            }


                            if (submitButton) {

                                submitButton.disabled =
                                    true;


                                submitButton.innerHTML =
                                    'Cadastrando...';
                            }
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | INICIAL
                |--------------------------------------------------------------------------
                */

                contarImagens();

                atualizarPrincipal();

            }
        );

    </script>

</body>

</html>