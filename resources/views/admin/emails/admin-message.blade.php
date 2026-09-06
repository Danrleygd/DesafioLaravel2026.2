<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $assunto }}
    </title>

</head>


<body
    style="
        margin: 0;
        padding: 0;
        background: #f5f3f8;
        font-family: Arial, Helvetica, sans-serif;
        color: #2c2731;
    "
>

    <table
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="
            width: 100%;
            padding: 40px 15px;
            background: #f5f3f8;
        "
    >

        <tr>

            <td align="center">


                <table
                    width="600"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="
                        width: 100%;
                        max-width: 600px;
                        background: white;
                        border-radius: 16px;
                        overflow: hidden;
                    "
                >


                    {{-- CABEÇALHO --}}

                    <tr>

                        <td
                            style="
                                padding: 30px 35px;
                                background: #60318f;
                                color: white;
                            "
                        >

                            <div
                                style="
                                    font-size: 27px;
                                    font-weight: bold;
                                "
                            >
                                D-tech
                            </div>


                            <div
                                style="
                                    margin-top: 5px;
                                    font-size: 12px;
                                    opacity: .75;
                                "
                            >
                                Comunicação da plataforma
                            </div>

                        </td>

                    </tr>


                    {{-- CONTEÚDO --}}

                    <tr>

                        <td
                            style="
                                padding: 35px;
                            "
                        >

                            <p
                                style="
                                    margin-top: 0;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >

                                Olá,

                                <strong>
                                    {{ $destinatario->nome }}
                                </strong>.

                            </p>


                            <h1
                                style="
                                    margin: 20px 0;
                                    color: #3c2e45;
                                    font-size: 22px;
                                "
                            >
                                {{ $assunto }}
                            </h1>


                            <div
                                style="
                                    color: #615866;
                                    font-size: 14px;
                                    line-height: 1.8;
                                "
                            >
                                {!! nl2br(e($conteudo)) !!}
                            </div>


                            <div
                                style="
                                    height: 1px;
                                    margin: 30px 0 20px;
                                    background: #ece7f0;
                                "
                            ></div>


                            <p
                                style="
                                    margin: 0;
                                    color: #918998;
                                    font-size: 11px;
                                    line-height: 1.6;
                                "
                            >

                                Mensagem enviada por

                                <strong>
                                    {{ $administrador->nome }}
                                </strong>

                                através da plataforma D-tech.

                            </p>

                        </td>

                    </tr>


                    {{-- RODAPÉ --}}

                    <tr>

                        <td
                            style="
                                padding: 20px;
                                background: #faf9fb;
                                color: #aaa2ae;
                                text-align: center;
                                font-size: 10px;
                            "
                        >
                            D-tech
                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>

</html>