<?php

namespace App\Utils;

use Filament\Notifications\Notification;

class NotificacaoUtils
{
    public function notificarSucesso($mensagem)
    {
        Notification::make()
            ->title('Sucesso!')
            ->body($mensagem)
            ->success()
            ->send();
    }

    public function notificarErro($mensagem)
    {
        Notification::make()
            ->title('Erro!')
            ->body($mensagem)
            ->danger()
            ->send();
    }
}
