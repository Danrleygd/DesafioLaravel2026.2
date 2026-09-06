<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $assunto }}</title>
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
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="
            width: 100%;
            background: #f5f3f8;
            padding: 36px 16px;
        "
    >
        <tr>

            <td align="center">

                <table
                    role="presentation"
                    width="600"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="
                        width: 100%;
                        max-width: 600px;
                        background: #ffffff;
                        border-radius: 16px;
                        overflow: hidden;
                    "
                >

                    <tr>

                        <td
                            style="
                                padding: 28px 34px;
                                background: #60318f;
                                color: #ffffff;
                            "
                        >

                            <div
                                style="
                                    font-size: 25px;
                                    font-weight: 700;
                                "
                            >
                                D-tech
                            </div>

                            <div
                                style="
                                    margin-top: 5px;
                                    color: rgba(255,255,255,.72);
                                    font-size: 12px;
                                "
                            >
                                Comunicação da plataforma
                            </div>

                        </td>

                    </tr>


                    <tr>

                        <td
                            style="
                                padding: 34px;
                            "
                        >

                            <p
                                style="
                                    margin: 0 0 20px;
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
                                    margin: 0 0 22px;
                                    color: #3a2d43;
                                    font-size: 22px;
                                    line-height: 1.35;
                                "
                            >
                                {{ $assunto }}
                            </h1>


                            <div
                                style="
                                    color: #5f5865;
                                    font-size: 14px;
                                    line-height: 1.8;
                                "
                            >
                                {!! nl2br(e($conteudo)) !!}
                            </div>


                            <div
                                style="
                                    height: 1px;
                                    margin: 30px 0 22px;
                                    background: #ece7f0;
                                "
                            ></div>


                            <p
                                style="
                                    margin: 0;
                                    color: #8b8390;
                                    font-size: 11px;
                                    line-height: 1.6;
                                "
                            >
                                Mensagem enviada por

                                <strong>
                                    {{ $administrador->nome }}
                                </strong>

                                através do sistema administrativo da D-tech.
                            </p>

                        </td>

                    </tr>


                    <tr>

                        <td
                            style="
                                padding: 20px 34px;
                                background: #faf9fb;
                                color: #99919d;
                                font-size: 10px;
                                text-align: center;
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