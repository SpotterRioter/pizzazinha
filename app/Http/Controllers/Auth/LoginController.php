<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Informe o e-mail do atendente.',
            'email.email' => 'Digite um e-mail válido.',
            'password.required' => 'Informe a senha de acesso.',
        ]);

        if (! Auth::attempt([...$credentials, 'ativo' => true])) {
            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha incorretos. Verifique os dados e tente novamente.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('login_at', now()->toIso8601String());
        $request->session()->put('ultima_atividade', now()->timestamp);

        return redirect()
            ->intended(route('dashboard'))
            ->with('sucesso', 'Bem-vindo(a), '.Auth::user()->name.'! Sessão iniciada com sucesso.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('sucesso', 'Você saiu do sistema com segurança.');
    }
}
