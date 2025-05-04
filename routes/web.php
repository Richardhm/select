<?php

use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\BemvindoController;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TabelaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/assinaturas/plano', [AssinaturaController::class, 'createIndividual'])->name('assinaturas.individual.create');
Route::post('/assinaturas/individual', [AssinaturaController::class, 'storeIndividual'])->name('assinaturas.individual.store');

Route::get('/bem-vindo/{user}', [BemvindoController::class, 'index'])->name('bemvindo');

Route::post('/callback', [CallbackController::class,'index']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class,"index"])
        ->middleware(['verified','check'])
        ->name('dashboard');

    Route::post('/cidades/origem', [DashboardController::class, 'getCidadesDeOrigem'])->name('cidades.origem');
    Route::post('/buscar_planos',[DashboardController::class,'buscar_planos'])->middleware(['verified'])->name('buscar_planos');

    Route::post('/dashboard/orcamento',[DashboardController::class,'orcamento'])->middleware(['verified'])->name('orcamento.montarOrcamento');
    Route::post("/pdf",[DashboardController::class,'criarPDF'])->middleware(['verified'])->name('gerar.imagem');

    /********* Configurações **************/
    //Route::group(function () {
        Route::get("/configuracoes", [ConfiguracoesController::class, 'index'])->name('configuracoes.index');

        Route::post('/configuracoes/cidades/', [ConfiguracoesController::class, 'cidadeStore'])->name('cidades.store');
        Route::delete('/configuracoes/cidades/{id}', [ConfiguracoesController::class, 'cidadeDestroy'])->name('cidades.destroy');

        Route::post('/administradoras/store', [ConfiguracoesController::class, 'storeAdministradora'])->name('administradoras.store');
        Route::delete('/administradoras/{administradora}', [ConfiguracoesController::class, 'administradoraDestroy'])->name('administradoras.destroy');

        Route::post('/planos', [ConfiguracoesController::class, 'storePlanos'])->name('planos.store');
        Route::delete('/planos/{plano}', [ConfiguracoesController::class, 'planosDestroy'])->name('planos.destroy');

        Route::get('/assinaturas-cidades/{assinatura}/cidades', [ConfiguracoesController::class, 'getCidades'])->name('assinaturas.cidades');
        Route::post('/assinaturas-cidades/vincular', [ConfiguracoesController::class, 'vincular'])->name('assinaturas.vincular');
        Route::post('/assinaturas-cidades/desvincular', [ConfiguracoesController::class, 'desvincular'])->name('assinaturas.desvincular');


        Route::post('/store/pdf', [ConfiguracoesController::class, 'storePdf'])->name('pdf.store');
        Route::delete('/pdf/{pdf}', [ConfiguracoesController::class, 'destroyPdf'])->name('pdf.destroy');

        Route::get('/pdf/{pdf}/edit', [ConfiguracoesController::class, 'editPdf'])->name('pdf.edit');
        Route::put('/pdf/{pdf}', [ConfiguracoesController::class, 'updatePdf'])->name('pdf.update');

        Route::post('/verificar/tabela', [ConfiguracoesController::class, 'verificar'])->name('tabelas.verificar');
        Route::post('/tabelas/salvar', [ConfiguracoesController::class, 'salvarTabela'])->name('tabelas.salvar');
        Route::post('/mudar/valor/tabela', [ConfiguracoesController::class, 'mudarTabela'])->name('corretora.mudar.valor.tabela');

        Route::post('/desconto', [ConfiguracoesController::class, 'storeDesconto'])->name('descontos.store');
        Route::delete('/desconto/{desconto}', [ConfiguracoesController::class, 'destroyDesconto'])->name('descontos.destroy');

        Route::get('/plano/administradoras/cidades', [ConfiguracoesController::class, 'index'])->name('admin-planos.index');
        Route::post('/plano/administradoras/cidades', [ConfiguracoesController::class, 'store'])->name('admin-planos.store');
        Route::delete('/plano/administradoras/cidades/{id}', [ConfiguracoesController::class, 'destroy'])->name('admin-planos.destroy');

        Route::post('/cupons', [ConfiguracoesController::class, 'storeCupon'])->name('cupons.store');

        Route::post('/config/filtrar-planos-por-admin', [ConfiguracoesController::class, 'planosPorAdministradora'])->name('filtrar.planos.admin');
        Route::post('/config/filtrar-tabelas-por-admin-plano', [ConfiguracoesController::class, 'tabelasPorAdminPlano'])->name('filtrar.tabelas.adminplano');


        Route::post("/config/carencia",[ConfiguracoesController::class, 'storeCarencia'])->name('carencia.store');

        Route::get('/carencia/detalhe', [ConfiguracoesController::class, 'detalheCarencia'])->name('carencia.detalhe');
        Route::delete('/carencia/deleteGrupo', [ConfiguracoesController::class, 'deleteGrupoCarencia'])->name('carencia.deleteGrupo');

        Route::post('/pdf-excecao', [ConfiguracoesController::class, 'storePdfExcecao'])->name('pdf-excecao.store');
        Route::delete('/pdf-excecao/{id}', [ConfiguracoesController::class, 'destroyPdfExcecao'])->name('pdf-excecao.destroy');



    //});
    /********* Fim Configurações **************/
    Route::post("/assinatura/trial/store", [AssinaturaController::class, 'storeTrial'])->name('assinaturas.trial.store')->middleware('apenasAdministradores');
    Route::get('/users/manage', [UserController::class, 'index'])->name('users.manage')
        ->middleware(['apenasAdministradores','check']);
    Route::get("/assinatura/alterar", [AssinaturaController::class, 'edit'])->name('assinatura.edit')->middleware('apenasAdministradores');
    Route::post('/users/editar/manager', [UserController::class, 'getUser'])->name('users.get');
    Route::post('/users/alterar',[UserController::class,'alterar'])->name('users.alterar');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/deletar', [UserController::class, 'deletar'])->name('deletar.user');
    Route::post('/users/manage', [UserController::class, 'storeUser'])->name('storeUser')->middleware('apenasAdministradores');

    Route::get('/assinatura/historico', [AssinaturaController::class, 'historicoPagamentos'])->middleware(['auth', 'verified','check'])->name('assinatura.historico');
    Route::get('/tabela_completa',[TabelaController::class,'index'])->name('tabela_completa.index')->middleware(['check']);


    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/dashboard/tabela/orcamento',[TabelaController::class,'orcamento'])->middleware(['auth', 'verified'])->name('orcamento.tabela.montarOrcamento');
require __DIR__.'/auth.php';
