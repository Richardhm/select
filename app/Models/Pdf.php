<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'plano_id',
        'tabela_origens_id',
        'linha01',
        'linha02',
        'linha03',
        'consultas_eletivas_total',
        'consultas_de_urgencia_total',
        'exames_simples_total',
        'exames_complexos_total',
        'terapias_especiais_total',

        'consultas_eletivas_parcial',
        'consultas_de_urgencia_parcial',
        'exames_simples_parcial',
        'exames_complexos_parcial',
        'terapias_especiais_parcial'

    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    // Relacionamento com Cidade (TabelaOrigem)
    public function cidade()
    {

        return $this->belongsTo(TabelaOrigens::class, 'tabela_origens_id');
    }



    protected $table = 'pdf';
}

