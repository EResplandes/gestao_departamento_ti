<?php

namespace App\Utils;
use App\Models\HistoricoChamado;

class HistoricoUtils
{
    public function criarHistorico()
    {
        HistoricoChamado::create([
            'observacao' => 'Chamado finalizado pelo usuário' . Auth::name(),
            'status_id' => 4,
            'chamado_id' => $request->input('id')
        ]);
    }
}
