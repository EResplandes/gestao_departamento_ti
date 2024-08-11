<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificaPerfilAtivoeAcessoDashbboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->status !== 'Ativo' || $user->tipo_usuario == 'Convencional') {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Sua conta não está ativa.']);
            }

            // Adicionalmente, você pode adicionar outras verificações, se necessário
        }

        return $next($request);
    }
}
