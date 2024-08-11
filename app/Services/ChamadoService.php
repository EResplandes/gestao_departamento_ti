<?php

namespace App\Services;

use App\Models\Chamado;
use App\Models\HistoricoChamado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Utils\NotificacaoUtils;

class ChamadoService
{

    protected $notificacao;

    public function __construct(NotificacaoUtils $notificacao)
    {
        $this->notificacao = $notificacao;
    }

    public function associarChamadoTecnico($chamado)
    {
        DB::beginTransaction();

        try {

            $userId = Auth::id();

            Chamado::where('id', $chamado)
                ->update([
                    'tecnico_id' => $userId,
                    'status_id' => 2
                ]);

            DB::commit();

            $this->notificacao->notificarSucesso('Chamado associado com sucesso!');

            return redirect()->back()->with('success', 'Chamado associado a você com sucesso!');
        } catch (\Exception $e) {

            DB::rollBack();

            $this->notificacao->notificarErro('Ocorreu algum erro, entre em contato com o Administrador!');

            return redirect()->back()->with('danger', 'Ocorreu algum erro, entre em contato com o Administrador!');
        }
    }

    public function finalizarChamado($request)
    {
        DB::beginTransaction();

        try {

            Chamado::where('id', $request->input('id'))
                ->update([
                    'status_id' => 4
                ]);

            HistoricoChamado::create([
                'observacao' => 'Chamado finalizado pelo usuário' . Auth::name(),
                'status_id' => 4,
                'chamado_id' => $request->input('id')
            ]);

            DB::commit();

            $this->notificacao->notificarSucesso('Chamado finalizado com sucesso!');
            
        } catch (\Exception $e) {

            DB::rollBack();

            $this->notificacao->notificarErro('Ocorreu algum erro, entre em contato com o Administrador!');

            return redirect()->back()->with('danger', 'Ocorreu algum erro, entre em contato com o Administrador!');
        }
    }
}
