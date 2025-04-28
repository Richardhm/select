<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfExcecao extends Model
{
    protected $table = 'pdf_excecao';

    protected $fillable = [
        'plano_id',
        'tabela_origens_id',
        'linha01',
        'linha02',
        'linha03',
        'consultas_eletivas_total',
        'pronto_atendimento',
        'faixa_1',
        'faixa_2',
        'faixa_3',
        'faixa_4',
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function cidade()
    {
        return $this->belongsTo(TabelaOrigens::class, 'tabela_origens_id');
    }
}
