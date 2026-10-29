@extends('layouts.guest')

@section('titulo', 'Entrar')

@section('conteudo')
<div class="relative flex min-h-screen overflow-hidden">
    <div class="pointer-events-none absolute -left-40 top-1/3 h-96 w-96 rounded-full bg-amber-500/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 -top-24 h-96 w-96 rounded-full bg-red-600/20 blur-3xl"></div>

    {{-- Painel de marca --}}
    <aside class="relative hidden w-1/2 flex-col justify-between border-r border-white/10 bg-gradient-to-br from-stone-900 via-stone-950 to-black p-12 lg:flex xl:p-16">
        <div class="flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-stone-950 shadow-lg shadow-amber-500/30">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m2 16 20 6-6-20A20 20 0 0 0 2 16" />
                    <path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4" />
                    <path d="M15 11h.01" />
                    <path d="M11 15h.01" />
                    <path d="M16 16h.01" />
                </svg>
            </span>
            <div>
                <p class="text-lg font-semibold tracking-tight">Forno di Pietra</p>
                <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Pizzaria Artesanal</p>
            </div>
        </div>

        <div class="max-w-md">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-4 py-1.5 text-xs font-medium text-amber-300">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                Sistema de gestão · SAEP
            </p>
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-white xl:text-5xl">
                Cada pedido no lugar certo,<br>
                <span class="text-amber-400">do forno à mesa.</span>
            </h1>
            <p class="mt-6 text-sm leading-relaxed text-stone-400">
                Gerencie clientes, sabores e entregas em um só lugar. Dados pessoais protegidos
                com criptografia AES-256 e acesso controlado por sessão com expiração automática.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-6 border-t border-white/10 pt-8 text-xs text-stone-400">
            <div>
                <p class="text-xl font-semibold text-white">LGPD</p>
                <p class="mt-1">CPF criptografado</p>
            </div>
            <div>
                <p class="text-xl font-semibold text-white">30 min</p>
                <p class="mt-1">Expiração de sessão</p>
            </div>
            <div>
                <p class="text-xl font-semibold text-white">100%</p>
                <p class="mt-1">Rastreio de pedidos</p>
            </div>
        </div>
    </aside>

    {{-- Formulário --}}
    <main class="relative flex w-full items-center justify-center px-6 py-12 lg:w-1/2">
        <div class="w-full max-w-sm">
            <div class="mb-10 flex items-center gap-3 lg:hidden">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-stone-950">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m2 16 20 6-6-20A20 20 0 0 0 2 16" />
                        <path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4" />
                        <path d="M15 11h.01" />
                        <path d="M11 15h.01" />
                        <path d="M16 16h.01" />
                    </svg>
                </span>
                <div>
                    <p class="font-semibold">Forno di Pietra</p>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-stone-500">Pizzaria Artesanal</p>
                </div>
            </div>

            <h2 class="text-2xl font-semibold tracking-tight text-white">Acesso dos atendentes</h2>
            <p class="mt-2 text-sm text-stone-400">Informe suas credenciais para abrir a comanda digital.</p>

            @if (session('sucesso'))
                <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    {{ session('sucesso') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                    <p class="flex items-center gap-2 font-medium">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.5"/></svg>
                        Não foi possível entrar
                    </p>
                    <ul class="mt-1.5 space-y-0.5 pl-6 text-red-200/90">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5" autocomplete="on">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-stone-400">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           placeholder="atendente@pizzaria.test"
                           class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-stone-500 outline-none transition focus:border-amber-500/60 focus:bg-white/10 focus:ring-2 focus:ring-amber-500/20 @error('email') border-red-500/60 @enderror">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-stone-400">Senha</label>
                    <input id="password" name="password" type="password" required
                           placeholder="••••••••"
                           class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-stone-500 outline-none transition focus:border-amber-500/60 focus:bg-white/10 focus:ring-2 focus:ring-amber-500/20 @error('password') border-red-500/60 @enderror">
                </div>

                <button type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:ring-offset-2 focus:ring-offset-stone-950">
                    Entrar no sistema
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <div class="mt-8 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-stone-500">Contas de demonstração</p>
                <div class="mt-2 space-y-1 font-mono text-xs text-stone-300">
                    <p>ana.souza@pizzaria.test <span class="text-stone-500">·</span> senha123</p>
                    <p>bruno.lima@pizzaria.test <span class="text-stone-500">·</span> senha123</p>
                    <p>carla.mendes@pizzaria.test <span class="text-stone-500">·</span> senha123</p>
                </div>
            </div>

            <p class="mt-8 text-center text-[11px] leading-relaxed text-stone-500">
                Acesso monitorado. A sessão expira automaticamente após 30 minutos de inatividade.
            </p>
        </div>
    </main>
</div>
@endsection
