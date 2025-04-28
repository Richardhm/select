<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"

          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Document</title>

    <style>



        @font-face {

            font-family: 'Roboto';

            src: url('{{ public_path("fonts/Roboto-Regular.ttf") }}') format('truetype');

            font-weight: normal;

            font-style: normal;

        }



        @font-face {

            font-family: 'Roboto';

            src: url('{{ public_path("fonts/Roboto-Bold.ttf") }}') format('truetype');

            font-weight: bold;

            font-style: normal;

        }



        html, body {

            width: 100%;

            height: 100%;

            margin: 0;

            padding: 0;

            font-family: 'Roboto', sans-serif !important;

        }

        tr {

            line-height: 1;

            vertical-align: top;

        }





        .container {

            position: absolute;

            top: 390px;

            left: 50%;

            transform: translateX(-50%);

            width: 97%;

            padding-bottom: 0 !important; /* Remove padding residual */

            margin-bottom: -10px; /* Compensa espaçamento vertical */

        }



        /* Novos estilos */

        .faixa-etaria {

            text-align: center;

            font-size: 1.5em;

            background-color: rgb(5,53,95);

            color:#FFF;

            font-weight:bold;

        }





        /* Estilos do footer */

        .footer {

            position: absolute;

            bottom: 0px;

            width: calc(100% - 40px); /* Ajusta a largura do footer */

            height: 200px; /* Define a altura do footer */

            padding: 0px;

            box-sizing: border-box;

        }



        .footer img {

            position: absolute;

            bottom:15px;

            right: 0px;

            width: 400px;

            height: 400px;

            border-radius: 50%;

        }



        .footer .middle, .footer .right {

            position: absolute;

            bottom:0px;





        }



        .footer .middle {

            bottom:30px;

            left: 10px;

            display:block;

            color: #0c0c0c;

            font-size:1.7em;

            line-height:1;

            padding:0;

        }



        .footer .middle p {

            margin: 0px;

            color:#FFFFFF;

            font-weight: bold;

            line-height:1;

            padding:0;

        }



        .footer .right {

            bottom:20px;

            right: 30px;

            text-align: left;

            display:block;

            font-size:2em;

        }



        .footer .right p {

            margin: 0;

            color:#FFFFFF;

            font-weight: bold;

        }



        .cidade_container {

            position:absolute;

            top:190px;

            left:42%;

            font-weight: bold;

            font-size: 2em;

            color:rgb(12,77,193);

        }



        .frase_container {

            position:absolute;

            top:260px;

            left:36%;

            font-weight: bold;

            font-size: 1.5em;

            color:rgb(12,77,193);

        }









        /**********************/

        .bloco-container {

            width: 100%;

            margin: 0 auto;

            border-spacing: 10px; /* Espaço entre blocos */

            border-collapse: separate;

        }

        .container table {

            border-collapse: separate;

            border-spacing: 0;

            margin-bottom: -5px; /* Compensa o espaçamento vertical residual */

        }



        .bloco {

            display: inline-table;

            background: white;

            border-radius: 60px;

            vertical-align: top;

            padding: 10px;

            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra suave */

        }



        .header-orange {

            background: #F88058;

            color: white;

            padding: 20px;

            border-radius: 55px 55px 0 0;

            font-weight: bold;

            font-size: 1.6em;



        }



        .subheader-blue {

            background: white;

            color: white;

            padding: 8px;

            font-size: 1.2em;

            color: rgb(8,73,189);

            font-weight:bold;

            text-align:center;

        }



        .linha-faixa {

            background: #FFF3CD;

            padding: 12px;

            margin: 8px 3px;

            border-radius: 8px;

            color: #366EBF;

            font-weight: bold;

            display: block;

        }



        .coluna-azul {

            background: white;

            font-weight:bold;

            padding: 8px;

            width: 50%;

            font-size: 1.2em;

            color: rgb(8,73,189);

            text-align:center;

        }



        .valor-copart {

            background: rgb(254,199,72);

            padding: 12px !important;

            margin: 5px 3px;

            border-radius: 8px;

            color: rgb(8,73,189);

            font-weight: bold;

            display: block;

            font-size:1.6em;

            text-align:center;

        }



        .valor-copart-laranja {

            background: #F88058;

            padding: 12px !important;

            margin: 5px 3px;

            border-radius: 0 0 55px 55px;

            color: white;

            font-weight: bold;

            display: block;

            font-size:1.6em;

            text-align:center;

        }



        .lista-coparticipacao {

            color: white;

            font-size: 1.2em;

            margin:0;

        }



        .lista-coparticipacao strong {

            display: block;

            margin: 10px 0;

            font-size:1.5em;

        }



        .procedimentos-container {

            width: 70%;

            margin-top: 1px;

            overflow: hidden; /* Contém os floats */

            background-color: rgb(12,77,193);

            border-radius: 55px;

            padding: 15px;

            min-height: 480px;

        }



        .procedimento-left {

            float: left;

            width: 60%;

            vertical-align: top;

        }



        .procedimento-right {

            float: right;

            width: 35%;

            vertical-align: top;

        }





        .linha-procedimento {

            margin: 0;

            padding:0;

        }



        .linha-procedimento span {

            font-size:1.3em;

            color:rgb(12,77,193);

            display:block;

            font-weight:bold;

            margin:0px;

            padding:0 0 0 30px;



        }





        .valor-procedimento {

            background: #FFF3CD;



            border-radius: 20px;

            display: inline-block;

            margin-left: 10px;

        }

    </style>

</head>

<body>

<img style="position: absolute;top: 0;left: 0;height: 100%;width: 100%;object-fit: cover;" src="layouts/modelo2.png" alt="Orçamento">

<p class="cidade_container" style="text-transform:uppercase;">{{$cidade}}</p>

<p class="frase_container" style="text-transform:uppercase;">{{$frase}}</p>



@php

    $dadosComOdontoComCopar = [];

    $dadosComOdontoSemCopar = [];

    $totalApartamento_com_copar = 0;

    $totalEnfermaria_com_copar = 0;

    $totalApartamento_sem_copar = 0;

    $totalEnfermaria_sem_copar = 0;

@endphp



@foreach($dados as $dado)

    @php

        $faixaEtaria = $dado->faixaEtaria->nome;

        $acomodacao = $dado->acomodacao_id;

        $valor = $dado->valor;

        $odonto = $dado->odonto;

        $coparticipacao = $dado->coparticipacao;

        $quantidade = $dado->quantidade;

        // Verifica se tem coparticipação

        $index = ($coparticipacao == 1) ? 'com_copar' : 'sem_copar';

        if (!isset($dadosComOdonto[$faixaEtaria])) {

            $dadosComOdonto[$faixaEtaria] = [

                'faixa_etaria_id' => $faixaEtaria,

                'apartamento_com_copar' => 0,

                'enfermaria_com_copar' => 0,

                'apartamento_sem_copar' => 0,

                'enfermaria_sem_copar' => 0,

                'quantidade' => $quantidade

            ];

        }

        $dadosComOdonto[$faixaEtaria]["{$acomodacao}_{$index}"] = $valor ?? 0;

    @endphp

@endforeach



@php

    // Calcular quantidade de blocos ativos

    $totalBlocos = 1; // Bloco Faixa Etária sempre existe

    if($com_coparticipacao == 1) $totalBlocos++;

    if($sem_coparticipacao == 1) $totalBlocos++;



    // Definir larguras baseado no número de blocos

    $widths = [

       1 => '32%',

        2 => '32%',

        3 => '32%'

    ];



    $margins = [

        1 => '0 auto',

        2 => '0 1%',

        3 => '0 1%'

    ];

@endphp







<div class="container">

    <table class="bloco-container" align="center" cellpadding="0" cellspacing="0">

        <tr>

            <!-- Bloco 1 - Faixa Etária -->

            <td class="bloco" style="width: 22%;{{$totalBlocos <= 2 ? 'margin-left:20%;' : 'margin-left:8%;'}}">

                <table width="100%">

                    <tr>

                        <td class="header-orange" style="text-align:center;font-size: 1.2em;">NOSSO PLANO</td>

                    </tr>





                    @foreach($dadosComOdonto as $faixaEtaria => $valores)

                        <tr>

                            <td>

                                <div class="valor-copart">

                                    {{ $faixaEtaria }}

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    <tr>

                        <td>

                            <div class="valor-copart-laranja">TOTAL</div>

                        </td>

                    </tr>

                </table>

            </td>

            @if($com_coparticipacao == 1)

                <!-- Bloco 2 - Com Coparticipação -->

                <td class="bloco" style="width: {{ $widths[$totalBlocos] }}; margin: {{ $margins[$totalBlocos] }};">

                    <table width="100%">

                        <tr>

                            <td colspan="2" class="header-orange" style="text-align:center;font-size:1.2em;">COM COPARTICIPAÇÃO</td>

                        </tr>



                        @foreach($dadosComOdonto as $faixaEtaria => $valores)

                            <tr>
                                <td colspan="2">
                                    <div class="valor-copart">
                                        @php
                                            $totalApartamento_com_copar += $valores['3_com_copar'];
                                        @endphp
                                        {{ number_format($valores['3_com_copar'], 2, ",", ".") }}
                                    </div>
                                </td>
                            </tr>

                        @endforeach

                        <tfoot>

                        <tr>



                            <td colspan="2">

                                <div class="valor-copart-laranja">

                                    {{number_format($totalApartamento_com_copar,2,",",".")}}

                                </div>

                            </td>



                        </tr>

                        </tfoot>

                    </table>



                </td>

            @endif



            @if($sem_coparticipacao == 1)

                <!-- Bloco 3 - Sem Coparticipação -->

                <td class="bloco" style="width: {{ $widths[$totalBlocos] }};{{$totalBlocos <= 2 ? 'margin-left:1%;' : 'margin-left:0%;'}} ">

                    <table width="100%">

                        <tr>

                            <td colspan="2" class="header-orange" style="text-align:center;font-size:1.2em;">SEM COPARTICIPAÇÃO *</td>

                        </tr>





                        @foreach($dadosComOdonto as $faixaEtaria => $valores)

                            <tr >

                                <td colspan="2">

                                    <div class="valor-copart">

                                        @php

                                            $totalEnfermaria_sem_copar += $valores['3_sem_copar'];

                                        @endphp

                                        {{ number_format($valores['3_sem_copar'], 2, ",", ".") }}

                                    </div>

                                </td>



                            </tr>

                        @endforeach

                        <tfoot>

                        <tr>



                            <td colspan="2">

                                <div class="valor-copart-laranja">

                                    {{number_format($totalEnfermaria_sem_copar,2,",",".")}}

                                </div>

                            </td>



                        </tr>

                        </tfoot>

                    </table>

        <tr>



        @endif

        </tr>

    </table>

    @php

        if($linhas <= 5) {

            echo '<div style="margin-bottom:50px"></div>';

        } else {

            echo '<div style="margin-bottom:30px"></div>';

        }

    @endphp



    <div style="width: 100%; margin-top: 1px;padding:0;">



        <div style="{{$com_coparticipacao == 1 ? 'width: 50%;float:left' : 'width:50%;float:left;margin-left:490px;' }}">

            <!-- Primeiro Bloco -->

            <div>

                <div class="lista-coparticipacao" style="margin: 0 0 0 50px; padding: 0; line-height: 1;color:rgb(12,77,193);">

                    <p style="font-size:1.2em;margin:0;padding:0;font-weight:bold;">* {{$pdf->linha01}}</p>

                    <p style="font-size:1em;margin:0 0 0 40px;padding:0;font-weight:bold;"> - {{$linha_01}}</p>

                    <p style="font-size:1em;margin:0 0 0 40px;padding:0;font-weight:bold;"> - {{$linha_02}}</p>

                    <p style="font-size:1.2em;margin:0;padding:0;font-weight:bold;">** {{$pdf->linha03}}</p>

                </div>

            </div>



            <!-- Segundo Bloco -->

            <div class="bloco-inferior" style="margin-top:50px;margin-left:50px;">

                @if($com_coparticipacao == 1)

                    <div class="procedimentos-container">



                        <div class="procedimento-left">

                            <span style="display:block;font-size:1.2em;color: white;font-weight:bold;margin-bottom:15px;margin-left:30px;">Procedimentos</span>

                            <div style="background-color:rgb(254,199,72);border-radius:45px;">

                                <div class="linha-procedimento">

                                    <span>Consultas Eletivas</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Consultas Urgência</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Exames Simples</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Exames Complexos</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Terapias Especiais</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Demais Terapias</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Internações</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>Cirurgias</span>

                                </div>

                            </div>

                        </div>



                        <div class="procedimento-right">

                            <span style="display:block;font-size:1.2em;color: white;font-weight:bold;margin-bottom:15px;margin-left:25px;">Copart Total</span>



                            <div style="background-color:rgb(254,199,72);border-radius:45px;">

                                <div class="linha-procedimento">

                                    <span>{{$pdf->consultas_eletivas_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->consultas_de_urgencia_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->exames_simples_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->exames_complexos_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->terapias_especiais_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->demais_terapias_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->internacoes_total}}</span>

                                </div>

                                <div class="linha-procedimento">

                                    <span>{{$pdf->cirurgia_total}}</span>

                                </div>

                            </div>



                        </div>



                        @endif

                    </div>

            </div>

        </div>

        @if($com_coparticipacao == 1)

            <div style="width: 50%;float:right;">

                <div style="margin-left:100px;">

                    <h3 style="color:rgb(12,77,193); font-size: 1.5em; margin: 0 0 10px 0;">CARÊNCIAS DE SAÚDE</h3>



                    <!-- 1º Bloco -->

                    <div style="padding-bottom: 10px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">24</span><br>

                                        <span style="font-size: 1.2em;">Hr.</span>

                                    </div>

                                </td>

                                <td style="vertical-align: middle;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">

                                        Urgência, Emergência e<br>

                                        Acidentes Pessoais

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>



                    <!-- 2º Bloco -->

                    <div style="padding-bottom: 10px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">30</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="vertical-align: middle;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">

                                        Consultas Médicas,<br>

                                        Exames Médicos Simples

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>



                    <!-- 3º Bloco -->

                    <div style="padding-bottom: 10px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">90</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td>

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">

                                        Exames Cardiol., Exames Imagem<br>

                                        Oftalmol., Otorrino Simples,<br>

                                        Raio X, Ultrassonografia

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>



                    <!-- 4º Bloco -->

                    <div style="padding-bottom: 10px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">180</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td>

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">

                                        Cirurgias, Internações, Exames<br>

                                        Alto Custo, Trat. Psicológico,<br>

                                        Terapia Ocupacional, Fisioterapia<br>

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>



                    <!-- 5º Bloco -->

                    <div style="padding-bottom: 10px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">300</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="vertical-align: middle;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">Parto</span>

                                </td>

                            </tr>

                        </table>

                    </div>



                    <!-- 6º Bloco -->

                    <div style="margin-bottom: 5px;">

                        <table>

                            <tr>

                                <td style="vertical-align: top; padding-right: 10px;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">720</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="vertical-align: middle;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193);text-align:left;display: block;font-weight: bold;">

                                        Doenças e Lesões<br>

                                        Pré-Existentes

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        @endif

        @if($com_coparticipacao == 0)

            <div style="width:100%;display:block;padding:0;clear:both;margin:0;">

                <div style="width:70%;margin:0 auto;height:100%;padding:0px;">



                    <div style="width:50%;float:left;">



                        <table style="width:100%; border-collapse: collapse; border-spacing: 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">24</span><br>

                                        <span style="font-size: 1.2em;">Hr.</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">

                                        Urgência, Emergência e<br>

                                        Acidentes Pessoais

                                    </span>

                                </td>

                            </tr>

                        </table>



                        <table style="width:100%; border-collapse: collapse; border-spacing: 0;margin:20px 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">30</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">

                                        Consultas Médicas,<br>

                                        Exames Médicos Simples

                                    </span>

                                </td>

                            </tr>

                        </table>





                        <table style="width:100%;border-collapse: collapse; border-spacing: 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">720</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">

                                        Doenças e Lesões<br>

                                        Pré-Existentes

                                    </span>

                                </td>

                            </tr>

                        </table>















                    </div>



                    <div style="width:50%;float:right;">



                        <table style="width:100%; border-collapse: collapse; border-spacing: 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">180</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">

                                        Cirurgias, Internações, Exames<br>

                                        Alto Custo, Trat. Psicológico,<br>

                                        Terapia Ocupacional, Fisioterapia<br>

                                    </span>

                                </td>

                            </tr>

                        </table>



                        <table style="width:100%; border-collapse: collapse; border-spacing: 0;margin:20px 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">300</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">Parto</span>

                                </td>

                            </tr>

                        </table>





                        <table style="width:100%; border-collapse: collapse; border-spacing: 0;">

                            <tr>

                                <td style="vertical-align: top; padding:0; width:1%;">

                                    <div style="border: 8px solid yellow;background-color:white;border-radius:10%;color:rgb(12,77,193); width: 80px; text-align: center; line-height: 1; padding: 12px;height:80px;">

                                        <span style="font-size: 1.4em; font-weight: bold;">90</span><br>

                                        <span style="font-size: 1.2em;">Dias</span>

                                    </div>

                                </td>

                                <td style="padding:0 0 0 10px; vertical-align: middle; white-space: nowrap;">

                                    <span style="font-size: 1.3em; color:rgb(12,77,193); font-weight: bold;">

                                        Exames Cardiol.,Exames Imagem<br>

                                        Oftalmol., Otorrino Simples,<br>

                                        Raio X, Ultrassonografia

                                    </span>

                                </td>

                            </tr>

                        </table>







                    </div>







                </div>



            </div>

        @endif

    </div>





















</div>



<div class="footer">











    <div class="middle">

        <p style="position:relative;">{{$nome}}</p>

        <p style="position:relative;">

            <span>{{$celular}}</span>

            <img src="whatsapp.png" alt="whatsapp" style="width:50px;height:50px;position:relative;top:7px;" />

        </p>

    </div>







    @if($image != "")

        <img src='{{$image}}' alt='ser Image'>

    @endif







</div>















</body>

</html>



