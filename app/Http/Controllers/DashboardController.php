<?php

namespace App\Http\Controllers;

use App\Models\Administradora;
use App\Models\AdministradoraPlano;
use App\Models\Assinatura;
use App\Models\Carencia;
use App\Models\Desconto;
use App\Models\EmailAssinatura;
use App\Models\Layout;
use App\Models\PdfExcecao;
use App\Models\Plano;
use App\Models\Tabela;
use App\Models\Pdf;
use App\Models\TabelaOrigens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDFFile;

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user(); // Usuário logado

        // Busca na tabela emails_assinatura
        $emailAssinatura = EmailAssinatura::where('email', $user->email)->first();

        $assinaturaId = $emailAssinatura?->assinatura_id; // Usando safe operator para evitar erro se não encontrar


            // Buscar só vínculos que pertencem à assinatura do usuário
            $vinculos = AdministradoraPlano::with(['administradora', 'plano', 'cidade'])
                ->where('assinatura_id', $assinaturaId)
                ->get();



            // Pegar administradoras e planos dos vínculos
            $administradoras = $vinculos->pluck('administradora')->unique('id')->values();





            $planos = $vinculos->pluck('plano')->unique('id')->values();
            // Buscar cidades pela assinatura
            //$cidades = $user->assinaturas->tabelasOrigens ?? collect();
            $cidades = $vinculos->pluck('cidade')->unique('id')->values();


            $estados = $vinculos->pluck('estado')->unique('id')->values();



        return view('dashboard',[
            'cidades' => $cidades,
            'administradoras' => $administradoras,
            'planos' => $planos,
            'estados' => $estados
        ]);
    }

    public function getCidadesDeOrigem(Request $request)
    {
        $uf = $request->input('uf'); // Pode ser 'id' se for id do estado

        $cidades = \DB::table('tabela_origens')
            ->where('uf', $uf)
            ->select('id', 'nome')
            ->orderBy('nome')
            ->get();

        return response()->json($cidades);
    }


    public function buscar_planos(Request $request)
    {
        $administradora_id = $request->input('administradora_id');
        $tabela_origens_id = $request->input('tabela_origens_id');
        $planos = DB::table('administradora_planos')
            ->where('administradora_id', $administradora_id)
            ->where('tabela_origens_id', $tabela_origens_id)
            ->pluck('plano_id');
        return response()->json(['planos' => $planos]);
    }

    public function orcamento(Request $request)
    {
        $ambulatorial = $request->ambulatorial;
        $sql = "";
        $chaves = [];
        $somar_linhas = 0;
        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
                $somar_linhas += (int) $v;
            }
        }
        $vidas = min($somar_linhas, 4);



        $keys = implode(",",$chaves);
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;
        $imagem_operadora = Administradora::find($operadora)->logo;
        $plano_nome = Plano::find($plano)->nome;
        $imagem_plano = Administradora::find($operadora)->logo;
        $cidade_nome = TabelaOrigens::find($cidade)->nome;



        if($ambulatorial == 0) {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->when(in_array($plano, [1]), function ($query) use ($vidas) {
                    return $query->where('tabelas.vidas', $vidas);
                })
                //->where('acomodacao_id',"!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            $desconto = Desconto::where("tabela_origens_id",$cidade)->where("plano_id",$plano)->where("administradora_id",$operadora)->count();
            $status_desconto = 0;
            if($desconto == 1) {
                $status_desconto = 1;
            }


            $status = $dados->contains('odonto', 0);
            $status_odonto = $dados->contains('odonto',1);
            return view("cotacao.cotacao2",[
                "dados" => $dados,
                "status_odonto" => $status_odonto,
                "operadora" => $imagem_operadora,
                "plano_nome" => $plano_nome,
                "cidade_nome" => $cidade_nome,
                "imagem_plano" => $imagem_plano,
                "status" => $status,
                "status_desconto" => $status_desconto
            ]);

        } else {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where('acomodacao_id',"=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            //return $dados;
            $status = $dados->contains('odonto', 0);

            $desconto = Desconto::where("tabela_origens_id",$cidade)->where("plano_id",$plano)->where("administradora_id",$operadora)->count();
            $status_desconto = 0;
            if($desconto == 1) {
                $status_desconto = 1;
            }

            return view("cotacao.cotacao-ambulatorial",[
                "dados" => $dados,
                "operadora" => $imagem_operadora,
                "plano_nome" => $plano_nome,
                "cidade_nome" => $cidade_nome,
                "imagem_plano" => $imagem_plano,
                "status" => $status,
                "status_desconto" => $status_desconto
            ]);
        }
    }

    public function criarPDF()
    {
        $com_coparticipacao = 1;
        $sem_coparticipacao = 0;
        //$status_desconto    = request()->status_desconto    == "true" ? 1 : 0;
        $apenasvalores      = request()->apenasvalores      == "true" ? 1 : 0;
        $tipo_documento     = request()->tipo_documento;

        $ambulatorial = request()->ambulatorial;
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;
        $odonto = request()->odonto;

        $sql = "";
        $chaves = [];
        $linhas = 0;
        $somar_linhas = 0;
        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
                $somar_linhas += (int) $v;
            }
        }

        $vidas = min($somar_linhas, 4);
        $linhas = count($chaves);
        $cidade_nome = TabelaOrigens::find($cidade)->nome;
        $plano_nome = Plano::find($plano)->nome;
        $linha_01 = "";
        $linha_02 = "";

        $texto_enfermaria = "";
        $texto_apartamento = "";

        // Definir arrays para cada categoria de plano
        $planosFamiliares100 = [1, 4];
        $planosFamiliares110 = [5];

        // Verificar a categoria do plano atual
        if (in_array($plano, $planosFamiliares100)) {
            $texto_enfermaria = "Familiar 100";
            $texto_apartamento = "Familiar 200";
        } elseif (in_array($plano, $planosFamiliares110)) {
            $texto_enfermaria = "Familiar 110";
            $texto_apartamento = "Familiar 120";
        } else {
            $texto_enfermaria = "Empresarial 100";
            $texto_apartamento = "Empresarial 200";
        }





        $cidade_uf = TabelaOrigens::find($cidade)->uf;
        $status_excecao = false;

        $admin_nome = Administradora::find($operadora)->nome;
        $odonto_frase = $odonto == 1 ? " c/ Odonto" : " s/ Odonto";
        $frase = $plano_nome.$odonto_frase;
        $keys = implode(",",$chaves);
        $imagem_user = "storage/".auth()->user()->imagem;

        $nome = auth()->user()->name;
        $celular = auth()->user()->phone;
        $corretora = auth()->user()->corretora_id;
        $status_carencia = 1;
        $status_desconto = request()->status_desconto == "true" ? 1 : 0;
        if($ambulatorial == 0) {
            $dadosPorPagina = 15;
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->when(in_array($plano, [1]), function ($query) use ($vidas) {
                    return $query->where('tabelas.vidas', $vidas);
                })
                ->where('tabelas.plano_id',$plano)

                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();







            $valor_desconto = 0;
            if($status_desconto) {
                $desconto = Desconto::where('plano_id', $plano)->where('tabela_origens_id', $cidade)->where('administradora_id',$operadora)->first();
                if($desconto) {
                    $valor_desconto = $desconto->valor;
                }

            }

//            $desconto = Desconto::where('plano_id', $plano)
//                ->where('tabela_origens_id', $cidade)
//                ->first();
//
//            $valor_desconto = "";
//            $status_desconto = 0;
//            if($desconto) {
//                $valor_desconto = $desconto->valor;
//                $status_desconto = 1;
//            }

            $layout = auth()->user()->layout_id;
            $layout_user = in_array($layout, [1, 2, 3, 4]) ? $layout : 1;
            $layout_folder = auth()->user()->isFolder() ?: '';
            $viewName = "cotacao.modelo{$layout_user}";
            if($apenasvalores == 0) {
                $pdf_excecao = PdfExcecao::where("plano_id",$plano)->where("tabela_origens_id",$cidade)->count();
                if($pdf_excecao == 1) {
                    $status_excecao = true;
                    $pdf_copar = PdfExcecao::where("plano_id",$plano)->where("tabela_origens_id",$cidade)->first();
                } else {
                    $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                        ->where('tabela_origens_id',$cidade)
                        ->exists();
                    if ($hasTabelaOrigens) {
                        $pdf_copar = Pdf::where('plano_id', $plano)
                            ->where('tabela_origens_id',$cidade)
                            ->first();

                        if($pdf_copar->linha02) {
                            $itens = explode('|', $pdf_copar->linha02);
                            $itensFormatados = array_map(function($item) {
                                return trim($item); // Remove espaços extras
                            }, $itens);
                            $linha_01 = $itensFormatados[0];
                            $linha_02 = $itensFormatados[1];
                        }


                    } else {
                        $pdf_copar = Pdf::where('plano_id', $plano)->first();
                        if(isset($pdf_copar->linha02) && $pdf_copar->linha02) {
                            $itens = explode('|', $pdf_copar->linha02);
                            $itensFormatados = array_map(function($item) {
                                return trim($item); // Remove espaços extras
                            }, $itens);
                            $linha_01 = $itensFormatados[0];
                            $linha_02 = $itensFormatados[1];
                        }

                    }
                }


                $carencia = Carencia::where("plano_id",$plano)->where("tabela_origens_id",$cidade)->get();





                $view = \Illuminate\Support\Facades\View::make($viewName,[
                    'com_coparticipacao' => $com_coparticipacao,
                    'sem_coparticipacao' => $sem_coparticipacao,
                    'apenas_valores' => $apenasvalores,
                    'folder' => $layout_folder,
                    'linha_01' => $linha_01,
                    'texto_enfermaria' => $texto_enfermaria,
                    'texto_apartamento' => $texto_apartamento,
                    //'carencia' => 0,
                    'linha_02' => $linha_02,
                    'carencia_texto' => $carencia,
                    'valor_desconto' => $valor_desconto,
                    'desconto' => $status_desconto,
                    //'carencias' => $carencias,
                    'image' => $imagem_user,
                    'dados' => $dados,
                    'pdf' => $pdf_copar,
                    'nome' => $nome,
                    'cidade' => $cidade_nome,
                    'plano_nome' => $plano_nome,
                    'odonto_frase' => $odonto_frase,
                    'administradora' => $admin_nome,
                    'frase' => $frase,
                    'carencia' => $status_carencia,
                    'status_desconto' => $status_desconto,
                    'odonto' => $odonto,
                    'celular' => $celular,
                    'status_excecao' => $status_excecao,
                    'linhas' => $linhas,
                    'corretora' => $corretora
                ]);
            } else {
                //cabecalhos

                $cabecalho = auth()->user()->layout_id;
                $cabecalho_user = in_array($cabecalho, [1, 2, 3, 4]) ? $cabecalho : 1;
                $cabecalhoName = "cotacao.cabecalho{$cabecalho_user}";

                $layout_folder = auth()->user()->isFolder() ?: '';



                $view = \Illuminate\Support\Facades\View::make($cabecalhoName,[
                    'com_coparticipacao' => $com_coparticipacao,
                    'sem_coparticipacao' => $sem_coparticipacao,
                    'apenas_valores' => $apenasvalores,
                    'cabecalho' => $cabecalho,
                    'folder' => $layout_folder,
                    'texto_enfermaria' => $texto_enfermaria,
                    'texto_apartamento' => $texto_apartamento,
                    //'carencias' => $carencias,
                    'dados' => $dados,
                    //'pdf' => $pdf_copar,
                    'linha_01' => $linha_01,
                    'linha_02' => $linha_02,
                    'nome' => $nome,
                    'cidade' => $cidade_nome,
                    'plano_nome' => $plano_nome,
                    'odonto_frase' => $odonto_frase,
                    'administradora' => $admin_nome,
                    'frase' => $frase,
                    'status_desconto' => $status_desconto,
                    'odonto' => $odonto,
                ]);
            }

            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
            $altura = match (true) {
                $somar_linhas === 1 => 350,
                $somar_linhas === 2 => 380,
                $somar_linhas === 3 => 420,
                $somar_linhas >= 4 && $somar_linhas <= 5 => 500,
                $somar_linhas >= 6 && $somar_linhas <= 7 => 580,
                default => 580,
            };

            if($tipo_documento == "pdf") {

                if ($apenasvalores == 1) {
                    $pdf = PDFFile::loadHTML($view)
                        //->setPaper('A3', 'portrait');
                        ->setPaper([0, 0, 595, $altura]); // Redimensiona o PDF
                    return $pdf->download($nome_img.".pdf");
                } else {
                    $pdf = PDFFile::loadHTML($view)
                        ->setPaper('A3', 'portrait');
                    return $pdf->download($nome_img.".pdf");

                }
            } else {

                $pdfPath = storage_path('app/temp/temp.pdf');

                if($apenasvalores == 1) {
                    $pdf = PDFFile::loadHTML($view)
                        ->setPaper([0, 0, 595, $altura]);
                } else {
                    $pdf = PDFFile::loadHTML($view)->setPaper('A3', 'portrait');
                }
                $pdf->save($pdfPath);
                $imagemPath = storage_path("app/temp/{$nome_img}.png");
                if (file_exists($imagemPath)) {
                    unlink($imagemPath);  // Exclui a imagem anterior se ela existir
                }

                if($apenasvalores == 1) {
                    $command = "gs -sDEVICE=pngalpha -r300 -dDEVICEWIDTHPOINTS=595 -dDEVICEHEIGHTPOINTS={$altura} -dPDFFitPage -dUseCropBox -dDetectDuplicateImages -dNOTRANSPARENCY -o {$imagemPath} {$pdfPath}";
                    exec($command, $output, $status);
                } else {
                    $command = "gs -sDEVICE=pngalpha -r300 -o {$imagemPath} {$pdfPath}";
                    exec($command, $output, $status);
                }

                if ($status !== 0 || !file_exists($imagemPath)) {
                    return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
                }

                return response()->download($imagemPath)->deleteFileAfterSend(true);

            }
        } else {

            $layout = auth()->user()->layout_id;
            $layout_user = in_array($layout, [1, 2, 3, 4]) ? $layout : 1;
            $viewName = "cotacao.modelo-ambulatorial{$layout_user}";

            $frase = "Ambulatorial ".$odonto_frase;

            $imagem_user = "storage/".auth()->user()->imagem;

            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',',$keys))
                ->get();

            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }

            $layout = auth()->user()->layout_id;
            $layout_user = in_array($layout, [1, 2, 3, 4]) ? $layout : 1;
            $viewName = "cotacao.cotacao-ambulatorial{$layout_user}";

            $valor_desconto = 0;
            if($status_desconto) {
                $desconto = Desconto::where('plano_id', $plano)->where('tabela_origens_id', $cidade)->where('administradora_id',$operadora)->first();
                if($desconto) {
                    $valor_desconto = $desconto->valor;
                }
            }

            if(($cidade_uf == "MT" || $cidade_uf == "MS") && $plano == 3) {
                $status_excecao = true;
                $pdf_copar = PdfExcecao::where('plano_id', $plano)->first();
            } else {
                $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->exists();
                if ($hasTabelaOrigens) {
                    $pdf_copar = Pdf::where('plano_id', $plano)
                        ->where('tabela_origens_id',$cidade)
                        ->first();

                    if($pdf_copar->linha02) {
                        $itens = explode('|', $pdf_copar->linha02);
                        $itensFormatados = array_map(function($item) {
                            return trim($item); // Remove espaços extras
                        }, $itens);
                        $linha_01 = $itensFormatados[0];
                        $linha_02 = $itensFormatados[1];
                    }


                } else {
                    $pdf_copar = Pdf::where('plano_id', $plano)->first();
                    if(isset($pdf_copar->linha02) && $pdf_copar->linha02) {
                        $itens = explode('|', $pdf_copar->linha02);
                        $itensFormatados = array_map(function($item) {
                            return trim($item); // Remove espaços extras
                        }, $itens);
                        $linha_01 = $itensFormatados[0];
                        $linha_02 = $itensFormatados[1];
                    }

                }
            }


            $view = \Illuminate\Support\Facades\View::make($viewName,[
                'com_coparticipacao' => 1,
                'sem_coparticipacao' => 1,
                'image' => $imagem_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'plano_nome' => "Individual",
                'linha_01' => $linha_01,
                'linha_02' => $linha_02,
                'nome' => $nome,
                'desconto' => $status_desconto,
                'valor_desconto' => $valor_desconto,
                'cidade' => $cidade_nome,
                'plano' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);

            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
            if($tipo_documento == "pdf") {

                $pdf = PDFFile::loadHTML($view)
                    ->setPaper('A3', 'portrait');
                return $pdf->download($nome_img.".pdf");

            } else {

                $pdfPath = storage_path('app/temp/temp.pdf');
                $pdf = PDFFile::loadHTML($view)->setPaper('A3', 'portrait');
                $pdf->save($pdfPath);
                $imagemPath = storage_path("app/temp/{$nome_img}.png");

                if (file_exists($imagemPath)) {
                    unlink($imagemPath);  // Exclui a imagem anterior se ela existir
                }

                $command = "gs -sDEVICE=pngalpha -r300 -o {$imagemPath} {$pdfPath}";  // -r150 é a resolução, pode ser ajustada

                exec($command, $output, $status);


                if ($status !== 0 || !file_exists($imagemPath)) {
                    return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
                }

                return response()->download($imagemPath)->deleteFileAfterSend(true);
            }
        }
    }



    public function criarPDFvolho()
    {
        $com_coparticipacao = request()->comcoparticipacao  == "true" ? 1 : 0;
        $sem_coparticipacao = request()->semcoparticipacao  == "true" ? 1 : 0;
        $apenasvalores      = request()->apenasvalores      == "true" ? 1 : 0;

        $layout = Layout::find(auth()->user()->layout_id);
        $ambulatorial = request()->ambulatorial;
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;
        $odonto = request()->odonto;
        $sql = "";
        $chaves = [];
        $linhas = 0;

        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
            }
        }


        $linhas = count($chaves);
        $cidade_nome = TabelaOrigens::find($cidade)->nome;
        $plano_nome = Plano::find($plano)->nome;

        $cidade_uf = TabelaOrigens::find($cidade)->uf;
        $status_excecao = false;
        if(($cidade_uf == "MT" || $cidade_uf == "MS") && $plano == 3) {
            $status_excecao = true;
            $pdf_copar = PdfExcecao::where('plano_id', $plano)->first();
        } else {
            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }
        }

        $admin_nome = Administradora::find($operadora)->nome;
        $odonto_frase = $odonto == 1 ? " c/ Odonto" : " s/ Odonto";
        $frase = $plano_nome.$odonto_frase;
        $keys = implode(",",$chaves);
        $image_user = "";
        if(auth()->user()->imagem) {
            //$image_user = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path(auth()->user()->image)));
            //$image_user = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/".auth()->user()->imagem)));
            $image_user = public_path("storage/".auth()->user()->imagem);
        }
        $nome = auth()->user()->name;
        $celular = auth()->user()->phone;
        $corretora = auth()->user()->corretora_id;
        $status_carencia = 1;
        $status_desconto = request()->status_desconto == "true" ? 1 : 0;
        if($ambulatorial == 0) {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();

            //dd($image_user);

            //$carencias = Carencia::where("plano_id",$plano)->get();
            $base64Image = "";
            $view = \Illuminate\Support\Facades\View::make("cotacao.cotacao3",[
                'com_coparticipacao' => $com_coparticipacao,
                'sem_coparticipacao' => $sem_coparticipacao,
                'apenas_valores' => $apenasvalores,
                'base64Image' => $base64Image,
                'layout' => $layout,
                'carencias' => "",
                'image' => $image_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'nome' => $nome,
                'cidade' => $cidade_nome,
                'plano_nome' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'status_carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'status_excecao' => $status_excecao,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);

            $view->with('background_image', public_path('semlogo.png'));

            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
            $pdfPath = storage_path('app/temp/temp.pdf');
            $pdf = PDFFile::loadHTML($view)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'dpi' => 300,
                    'defaultPaperSize' => 'a4',
                    'debugCss' => false,
                    'viewportSize' => '1920x1080'
                ]);








            $imagemPath = storage_path("app/temp/{$nome_img}.png");
            $pdf->save($pdfPath);

            if (file_exists($imagemPath)) {
                unlink($imagemPath);  // Exclui a imagem anterior se ela existir
            }
            //$command = "gs -sDEVICE=pngalpha -r300 -dUseCropBox -o {$imagemPath} {$pdfPath}";
            $command = "gs -sDEVICE=pngalpha -r300 -dUseCropBox -dPDFFitPage -o {$imagemPath} {$pdfPath}";


            exec($command, $output, $status);


            if ($status !== 0 || !file_exists($imagemPath)) {
                return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
            }

            return response()
                ->download($imagemPath)
                ->deleteFileAfterSend(true);












//            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
//            $pdfPath = storage_path('app/temp/temp.pdf');
//            PDFFile::loadHTML($view)->save($pdfPath);
//            $imagemPath = storage_path("app/temp/{$nome_img}.png");
//
//            if (file_exists($imagemPath)) {
//                unlink($imagemPath);  // Exclui a imagem anterior se ela existir
//            }
//
//            $command = "gs -sDEVICE=pngalpha -r300 -o {$imagemPath} {$pdfPath}";  // -r150 é a resolução, pode ser ajustada
//
//            exec($command, $output, $status);
//
//
//            if ($status !== 0 || !file_exists($imagemPath)) {
//                return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
//            }
//
//            return response()
//                ->download($imagemPath)
//                ->deleteFileAfterSend(true);






        } else {

            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }
            $view = \Illuminate\Support\Facades\View::make("cotacao.cotacao-ambulatorial-pdf",[
                'image' => $image_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'nome' => $nome,
                'cidade' => $cidade_nome,
                'plano' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'status_carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);
            $pdf = PDFFile::loadHTML($view);
            return $pdf->stream("teste.pdf");
        }
    }

}
