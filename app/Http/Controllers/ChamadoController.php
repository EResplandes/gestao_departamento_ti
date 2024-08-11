<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Services\ChamadoService;
use Illuminate\Http\Request;

class ChamadoController extends Controller
{
    protected $chamadoService;

    public function __construct(ChamadoService $chamadoService)
    {
        $this->chamadoService = $chamadoService;
    }

    public function associarChamadoTecnico($chamado)
    {
        $query = $this->chamadoService->associarChamadoTecnico($chamado);
        return $query;
    }

    public function finalizarChamado(Request $request)
    {
        $query = $this->chamadoService->finalizarChamado($request);
        return $query;
    }
}
