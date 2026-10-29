<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $limite = (int) config('session.lifetime'); // minutos
        $loginAt = $request->session()->get('login_at');

        if (! $loginAt) {
            $request->session()->put('login_at', now()->toIso8601String());
            $request->session()->put('ultima_atividade', now()->timestamp);

            return $next($request);
        }

        $ultimaAtividade = (int) $request->session()->get('ultima_atividade', 0);

        if ($ultimaAtividade > 0 && (now()->timestamp - $ultimaAtividade) > ($limite * 60)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Sua sessão expirou por inatividade. Faça login novamente.']);
        }

        $request->session()->put('ultima_atividade', now()->timestamp);

        return $next($request);
    }
}
