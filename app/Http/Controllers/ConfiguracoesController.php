<?php

namespace App\Http\Controllers;

use App\Models\AdministradoraPlano;
use App\Models\Pdf;
use Illuminate\Http\Request;
use App\Models\Administradora;
use App\Models\Plano;
use App\Models\Tabela;
use Illuminate\Support\Facades\DB;

class ConfiguracoesController extends Controller
{
    public function index()
    {
        return view('configuracoes.index');
    }

    public function verificar(Request $request)
    {

        $administradora_id = $request->administradora;
        $plano_id = $request->planos;
        $tabela_origem_id = $request->tabela_origem;
        $vidas = $request->vidas;
        $coparticipacao = $request->coparticipacao == "sim" ? 1 : 0;
        $odonto = $request->odonto == "sim" ? 1 : 0;

        $tabela = DB::table('tabelas')
            ->where("administradora_id",$administradora_id)
            ->where("tabela_origens_id",$tabela_origem_id)
            ->where("plano_id",$plano_id)
            ->where("vidas",$vidas)
            ->where("coparticipacao",$coparticipacao)
            ->where("odonto",$odonto)
            ->select("acomodacao_id", "valor","id")
            ->get();



        if($tabela->count() >= 1) {
            $ta = $tabela->map(function ($item) {

                $item->valor_formatado = number_format($item->valor, 2, ',', '.');
                return $item;
            });
            return $ta;
        } else {
            return "empty";
        }
    }

    public function planosPorAdministradora(Request $request)
    {

        $administradoraIds = $request->input('administradora_id');

        $planos = \DB::table('tabelas')
            ->join('planos', 'planos.id', '=', 'tabelas.plano_id')
            ->where('tabelas.administradora_id', $administradoraIds)
            ->select('planos.id', 'planos.nome')
            ->groupBy('planos.id', 'planos.nome')
            ->get();

        return response()->json($planos);
    }

    public function tabelasPorAdminPlano(Request $request)
    {
        $administradoraIds = $request->input('administradora_id');
        $planoIds = $request->input('plano_id');

        $tabelas = \DB::table('tabelas')
            ->join('tabela_origens', 'tabela_origens.id', '=', 'tabelas.tabela_origens_id')
            ->where('tabelas.administradora_id', $administradoraIds)
            ->where('tabelas.plano_id', $planoIds)
            ->select('tabela_origens.id', 'tabela_origens.nome')
            ->groupBy('tabela_origens.id', 'tabela_origens.nome')
            ->get();

        return response()->json($tabelas);
    }




    public function salvarTabela(Request $request)
    {
        foreach($request->valoresApartamento as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->vidas = $request->vidas;
            $tabela->acomodacao_id = 1;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);
            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresApartamento[$k]);

            if(!$tabela->save()) {
                return "error";
            }
        }

        foreach($request->valoresEnfermaria as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->vidas = $request->vidas;
            $tabela->acomodacao_id = 2;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);

            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresEnfermaria[$k]);

            if(!$tabela->save()) {
                return "error";
            }
        }

        foreach($request->valoresAmbulatorial as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->vidas = $request->vidas;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->acomodacao_id = 3;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);

            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresAmbulatorial[$k]);


            if(!$tabela->save()) {
                return "error";
            }
        }

        return "sucesso";

    }








    public function storeAdministradora(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store(
                '', // Não usar subdiretório
                ['disk' => 'administradoras'] // Especificar o disco
            );
            $data['logo'] = 'administradoras/' . $path; // Adiciona o caminho completo
        }

        Administradora::create($data);
        return back()->with('success', 'Administradora cadastrada!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assinatura_id' => 'required',
            'administradora_id' => 'required',
            'plano_id' => 'required',
            'tabela_origem_id' => 'required',
        ]);

        $assinaturaId = $request->assinatura_id;
        $administradoraId = $request->administradora_id;
        $planoId = $request->plano_id;
        $tabelas = $request->tabela_origem_id ?? "";

        $inserts = [];

        //foreach ($assinaturas as $assinaturaId) {
        //foreach ($administradoras as $administradoraId) {
        //foreach ($planos as $planoId) {
        foreach ($tabelas as $tabelaId) {

            // Verifica se já existe antes de adicionar no array
            $exists = AdministradoraPlano::where([
                'administradora_id' => $administradoraId,
                'plano_id' => $planoId,
                'tabela_origens_id' => $tabelaId,
                'assinatura_id' => $assinaturaId
            ])->exists();

            if (!$exists) {
                $inserts[] = [
                    'administradora_id' => $administradoraId,
                    'plano_id' => $planoId,
                    'tabela_origens_id' => $tabelaId,
                    'assinatura_id' => $assinaturaId,
                    'created_at' => now(),  // Necessário se sua tabela usa timestamps
                    'updated_at' => now(),
                ];
            }
        }
        //}
        //}
        //}

        if (!empty($inserts)) {
            AdministradoraPlano::insert($inserts);
        }

        return response()->json(['success' => 'Associações criadas com sucesso!']);
    }


    public function administradoraDestroy(Administradora $administradora)
    {
        try {
            if ($administradora->dependentes()->exists()) {
                return back()->with('error', 'Existem registros vinculados!');
            }


            if ($administradora->logo) {
                $filename = str_replace('administradoras/', '', $administradora->logo);
                Storage::disk('administradoras')->delete($filename);
            }

            $administradora->delete();
            return back()->with('success', 'Administradora excluída!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function storePlanos(Request $request)
    {

        $request->validate([
            'nome' => 'required|string|max:255',
            'empresarial' => 'nullable|boolean'
        ]);

        Plano::create([
            'nome' => $request->nome,
            'empresarial' => $request->filled('empresarial')
        ]);

        return back()->with('success', 'Plano cadastrado!');
    }

    public function planosDestroy(Plano $plano)
    {
        try {
            if ($plano->dependentes()->exists()) {
                return back()->with('error', 'Existem registros vinculados!');
            }

            $plano->delete();
            return back()->with('success', 'Plano excluído!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    public function detalheCarencia(Request $request)
    {
        $plano_id = $request->get('plano_id');
        $tabela_origens_id = $request->get('tabela_origens_id');

        $plano = \App\Models\Plano::findOrFail($plano_id);
        $cidade = \App\Models\TabelaOrigens::findOrFail($tabela_origens_id);

        $carencias = \App\Models\Carencia::where('plano_id', $plano_id)
            ->where('tabela_origens_id', $tabela_origens_id)
            ->orderBy('id')
            ->get();

        return view('carencia.detalhe', compact('plano', 'cidade', 'carencias'));
    }

    public function storePdf(Request $request)
    {

        if($request->linha02_part1 && $request->linha02_part2) {
            $request->merge([
                'linha02' => $request->linha02_part1 . '|' . $request->linha02_part2
            ]);
        }



        $data = $request->validate([
            'plano_id' => 'required|exists:planos,id',
            'tabela_origens_id' => 'nullable|exists:tabela_origens,id',
            'linha01' => 'nullable|string|max:40',
            'linha02_part1' => 'nullable|string|max:35',
            'linha02_part2' => 'nullable|string|max:35',
            'linha03' => 'nullable|string|max:40',
            // Adicionar validação para todos os campos total/parcial
            'consultas_eletivas_total' => 'nullable|string',
            'consultas_de_urgencia_total' => 'nullable|string',
            'exames_simples_total' => 'nullable|string',
            'exames_complexos_total' => 'nullable|string',
            'terapias_especiais_total' => 'nullable|string',
            'consultas_eletivas_parcial' => 'nullable|string',
            'exames_simples_parcial' => 'nullable|string',
            'exames_complexos_parcial' => 'nullable|string',
            'terapias_especiais_parcial' => 'nullable|string',
            'demais_terapias_parcial' => 'nullable|string',

        ]);

        if($data['linha02_part1'] && $data['linha02_part2']) {
            $data['linha02'] = implode('|', [
                $data['linha02_part1'] ?? '',
                $data['linha02_part2'] ?? ''
            ]);
        }




        Pdf::create($data);
        return back()->with('success', 'Configuração salva!');
    }



    public function deleteGrupoCarencia(Request $request)
    {
        $plano_id = $request->get('plano_id');
        $tabela_origens_id = $request->get('tabela_origens_id');

        \App\Models\Carencia::where('plano_id', $plano_id)
            ->where('tabela_origens_id', $tabela_origens_id)
            ->delete();

        return redirect()->route('configuracoes.index')
            ->with('success', 'Grupo de carências excluído com sucesso!');
    }





    public function storeCarencia(Request $request)
    {
        $plano_id           = $request->input('plano_id');
        $tabela_origens_id  = $request->input('tabela_origens_id');

        $carencia = \App\Models\Carencia::where('plano_id', $plano_id)
            ->where('tabela_origens_id', $tabela_origens_id)
            ->count();

        if($carencia >= 1) {
            \App\Models\Carencia::where('plano_id', $plano_id)
                ->where('tabela_origens_id', $tabela_origens_id)
                ->delete();
        }

        // Cria os 6 registros novos
        for ($i = 1; $i <= 6; $i++) {
            \App\Models\Carencia::create([
                'plano_id'          => $plano_id,
                'tabela_origens_id' => $tabela_origens_id,
                'tempo'             => $request->input("tempo_{$i}"),
                'detalhe'           => $request->input("detalhe_{$i}"),

            ]);
        }
        return response()->json([
            'success' => true
        ]);
    }







}
