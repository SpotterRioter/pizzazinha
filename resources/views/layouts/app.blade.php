<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Painel') · {{ config('app.name', 'Pizzaria') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $usuario = auth()->user();
    $iniciais = collect(preg_split('/\s+/', trim($usuario?->name ?? '')))
        ->filter()
        ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
        ->take(2)
        ->implode('');
    $restanteSegundos = max(0, (int) config('session.lifetime') * 60 - (now()->timestamp - (int) session('ultima_atividade', now()->timestamp)));
    $horaExpiracao = now()->addSeconds($restanteSegundos)->format('H:i');
    $menu = [
        ['rota' => 'dashboard', 'ativo' => 'dashboard', 'rotulo' => 'Painel', 'icone' => 'home'],
        ['rota' => 'clientes.index', 'ativo' => 'clientes.*', 'rotulo' => 'Clientes', 'icone' => 'users'],
        ['rota' => 'pizzas.index', 'ativo' => 'pizzas.*', 'rotulo' => 'Pizzas', 'icone' => 'pizza'],
        ['rota' => 'pedidos.index', 'ativo' => 'pedidos.*', 'rotulo' => 'Pedidos', 'icone' => 'receipt'],
    ];
@endphp
<body class="min-h-screen bg-stone-100 font-sans text-stone-800 antialiased">
<div class="flex min-h-screen">

    {{-- Sidebar desktop --}}
    <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col border-r border-stone-800 bg-stone-950 lg:flex">
        <div class="flex items-center gap-3 px-7 py-7">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-stone-950 shadow-lg shadow-amber-500/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m2 16 20 6-6-20A20 20 0 0 0 2 16" />
                    <path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4" />
                    <path d="M15 11h.01" />
                    <path d="M11 15h.01" />
                    <path d="M16 16h.01" />
                </svg>
            </span>
            <div>
                <p class="text-sm font-semibold tracking-tight text-white">Forno di Pietra</p>
                <p class="text-[10px] uppercase tracking-[0.2em] text-stone-500">Gestão de pizzaria</p>
            </div>
        </div>

        <nav class="mt-2 flex-1 space-y-1 px-4">
            <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-stone-600">Operação</p>
            @foreach ($menu as $item)
                @php $ativo = request()->routeIs($item['ativo']); @endphp
                <a href="{{ route($item['rota']) }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition {{ $ativo ? 'bg-amber-500 text-stone-950 shadow-md shadow-amber-500/10' : 'text-stone-400 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $ativo ? 'bg-stone-950/10' : 'bg-white/5 text-stone-400 group-hover:text-amber-400' }}">
                        @switch($item['icone'])
                            @case('home')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/></svg>
                                @break
                            @case('users')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 5.2a3 3 0 0 1 0 5.6"/><path d="M18 20a6.4 6.4 0 0 0-2.2-4.8"/></svg>
                                @break
                            @case('pizza')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/><path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/></svg>
                                @break
                            @default
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/></svg>
                        @endswitch
                    </span>
                    {{ $item['rotulo'] }}
                    @if ($ativo)
                        <svg class="ml-auto h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="mx-4 mb-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-sm font-semibold text-amber-400 ring-1 ring-amber-500/30">
                    {{ $iniciais ?: 'AT' }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-white">{{ $usuario?->name }}</p>
                    <p class="truncate text-[11px] text-stone-400">{{ $usuario?->cargo }}</p>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 rounded-lg bg-stone-900/80 px-2.5 py-2 text-[11px] text-stone-400">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                <span>Sessão expira às <span class="font-medium text-stone-200" id="sessao-hora" data-restante="{{ $restanteSegundos }}">{{ $horaExpiracao }}</span></span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-xs font-medium text-stone-300 transition hover:border-red-500/40 hover:bg-red-500/10 hover:text-red-300">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                    Sair do sistema
                </button>
            </form>
        </div>
    </aside>

    {{-- Conteúdo --}}
    <div class="flex min-w-0 flex-1 flex-col lg:pl-72">
        {{-- Topbar mobile --}}
        <header class="sticky top-0 z-30 border-b border-stone-800 bg-stone-950 px-4 py-3 lg:hidden">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-stone-950">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/><path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/></svg>
                    </span>
                    <span class="text-sm font-semibold text-white">Forno di Pietra</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-stone-300">Sair</button>
                </form>
            </div>
            <nav class="mt-3 flex gap-1.5 overflow-x-auto pb-1">
                @foreach ($menu as $item)
                    @php $ativo = request()->routeIs($item['ativo']); @endphp
                    <a href="{{ route($item['rota']) }}"
                       class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $ativo ? 'bg-amber-500 text-stone-950' : 'bg-white/5 text-stone-400' }}">
                        {{ $item['rotulo'] }}
                    </a>
                @endforeach
            </nav>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-9">
            @if (session('sucesso'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/></svg>
                    <span>{{ session('sucesso') }}</span>
                </div>
            @endif

            @if (session('erro'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.5"/></svg>
                    <span>{{ session('erro') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                    <p class="font-medium">Revise os campos abaixo:</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-stone-900 sm:text-3xl">@yield('titulo', 'Painel')</h1>
                    <p class="mt-1.5 max-w-2xl text-sm text-stone-500">@yield('subtitulo')</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @yield('acoes')
                </div>
            </div>

            @yield('conteudo')

            <footer class="mt-12 flex flex-wrap items-center justify-between gap-3 border-t border-stone-200 pt-5 text-xs text-stone-400">
                <p>{{ config('app.name') }} · Sistema de Gestão de Pizzaria</p>
                <p>Dados pessoais protegidos conforme a LGPD</p>
            </footer>
        </main>
    </div>
</div>

<script>
    (function () {
        const el = document.getElementById('sessao-hora');
        if (!el) return;

        let restante = Number(el.dataset.restante) || 0;

        const render = () => {
            if (restante <= 0) {
                el.textContent = 'expirada';
                return;
            }
            const data = new Date(Date.now() + restante * 1000);
            el.textContent = data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        };

        render();
        setInterval(() => {
            restante = Math.max(0, restante - 1);
            render();
        }, 1000);
    })();
</script>
</body>
</html>
