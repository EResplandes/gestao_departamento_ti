<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoChamado extends Model
{
    use HasFactory;

    protected $table = 'historico_chamados';

    protected $fillable = [
        'observacao',
        'status_id',
        'chamado_id'
    ];

}
