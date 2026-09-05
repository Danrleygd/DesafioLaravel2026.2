@php

    $editando =
        isset($produto)
        &&
        $produto !== null;


    $prefix =
        $prefix
        ?? (
            $editando
                ? 'edit-' . $produto->id
                : 'create'
        );


    $fotoAtual =
        $editando
            ? (
                $produto
                    ->fotos
                    ->firstWhere(
                        'principal',
                        true
                    )?->foto
                ??
                $produto->foto
            )
            : null;


    $fotoUrl = null;


    if ($fotoAtual) {

        $fotoUrl =
            str_starts_with(
                $fotoAtual,
                'http://'
            )
            ||
            str_starts_with(
                $fotoAtual,
                'https://'
            )
                ? $fotoAtual
                : asset(
                    'storage/' .
                    ltrim(
                        $fotoAtual,
                        '/'
                    )
                );
    }

@endphp


<div class="pm-form-grid">

    {{-- FOTO --}}
    <div class="pm-form-group pm-form-full">

        <label>
            Foto do produto
            {{ !$editando ? '*' : '' }}
        </label>


        <div class="pm-photo-area">

            <div
                class="pm-photo-preview"
                id="preview-{{ $prefix }}"
            >

                @if($fotoUrl)

                    <img
                        src="{{ $fotoUrl }}"
                        alt="Foto do produto"
                    >

                @else

                    <span>
                        Sem imagem
                    </span>

                @endif

            </div>


            <div>

                <label
                    for="foto-{{ $prefix }}"
                    class="pm-select-photo"
                >
                    {{ $editando
                        ? 'Alterar foto'
                        : 'Selecionar foto'
                    }}
                </label>


                <input
                    type="file"
                    name="foto"
                    id="foto-{{ $prefix }}"
                    class="pm-photo-input"
                    data-preview-target="preview-{{ $prefix }}"
                    accept=".jpg,.jpeg,.png,.webp"
                    {{ !$editando ? 'required' : '' }}
                    hidden
                >


                <small>
                    JPG, PNG ou WEBP.
                    Máximo 4 MB.
                </small>

            </div>

        </div>

    </div>


    {{-- NOME --}}
    <div class="pm-form-group pm-form-full">

        <label
            for="nome-{{ $prefix }}"
        >
            Nome *
        </label>

        <input
            type="text"
            name="nome"
            id="nome-{{ $prefix }}"
            maxlength="150"
            value="{{ old(
                'nome',
                $editando
                    ? $produto->nome
                    : ''
            ) }}"
            required
        >

    </div>


    {{-- PREÇO --}}
    <div class="pm-form-group">

        <label
            for="preco-{{ $prefix }}"
        >
            Preço *
        </label>

        <input
            type="number"
            name="preco"
            id="preco-{{ $prefix }}"
            min="0.01"
            step="0.01"
            value="{{ old(
                'preco',
                $editando
                    ? $produto->preco
                    : ''
            ) }}"
            required
        >

    </div>


    {{-- QUANTIDADE --}}
    <div class="pm-form-group">

        <label
            for="quantidade-{{ $prefix }}"
        >
            Quantidade *
        </label>

        <input
            type="number"
            name="quantidade"
            id="quantidade-{{ $prefix }}"
            min="0"
            value="{{ old(
                'quantidade',
                $editando
                    ? $produto->quantidade
                    : ''
            ) }}"
            required
        >

    </div>


    {{-- CATEGORIA --}}
    <div class="pm-form-group pm-form-full">

        <label
            for="categoria-{{ $prefix }}"
        >
            Categoria *
        </label>


        <select
            name="categoria_id"
            id="categoria-{{ $prefix }}"
            required
        >

            <option value="">
                Selecione uma categoria
            </option>


            @foreach($categorias as $categoria)

                <option
                    value="{{ $categoria->id }}"
                    @selected(
                        old(
                            'categoria_id',
                            $editando
                                ? $produto->categoria_id
                                : ''
                        )
                        ==
                        $categoria->id
                    )
                >
                    {{ $categoria->nome }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- DESCRIÇÃO --}}
    <div class="pm-form-group pm-form-full">

        <label
            for="descricao-{{ $prefix }}"
        >
            Descrição *
        </label>

        <textarea
            name="descricao"
            id="descricao-{{ $prefix }}"
            maxlength="2000"
            rows="6"
            required
        >{{ old(
            'descricao',
            $editando
                ? $produto->descricao
                : ''
        ) }}</textarea>

    </div>

</div>