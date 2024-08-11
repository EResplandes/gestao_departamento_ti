<?php

namespace App\Services;

use App\Models\User;
use App\Utils\NotificacaoUtils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{

    protected $notificacao;

    public function __construct(NotificacaoUtils $notificacao)
    {
        $this->notificacao = $notificacao;
    }

    public function alterarStatusUsuario($id)
    {
        DB::beginTransaction();

        try {
            $usuarioLogado =  User::find($id);
            $statusAtual = $usuarioLogado->status;

            switch ($statusAtual) {
                case 'Ativo':

                    User::where('id', $id)->update(['status' => 'Desativado']);
                    $this->notificacao->notificarSucesso('Usuário desativado com sucesso!');
                    break;

                case 'Desativado':

                    User::where('id', $id)->update(['status' => 'Ativo']);
                    $this->notificacao->notificarSucesso('Usuário ativado com sucesso!');
                    break;
            }

            DB::commit();
            return redirect()->back()->with('success', 'Usuário desativado com sucesso!');
        } catch (\Exception $e) {

            DB::rollBack();

            $this->notificacao->notificarErro('Ocorreu algum erro, entre em contato com o Administrador!');
        }
    }
}
