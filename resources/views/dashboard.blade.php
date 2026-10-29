@extends('layouts.app')

@section('titulo', 'Olá, '.auth()->user()->name)
@section('subtitulo', 'Aqui está o resumo da operação da pizzaria · '.now()->format('d/m/Y').'.')

@section('acoes')
    <a href="{{ route('pedidos.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Novo pedido
    </a>
    <a href="{{ route('clientes.create') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 shadow-sm transition hover:border-stone-400 hover:bg-stone-50">
        Cadastrar cliente
    </a>
@endsection

@section('conteudo')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <a href="{{ route('clientes.index') }}" class="group rounded-2xl border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-amber-300">
        <div class="flex items-start justify-between">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 5.2a3 3 0 0 1 0 5.6"/><path d="M18 20a6.4 6.4 0 0 0-2.2-4.8"/></svg>
            </span>
            <svg class="h-4 w-4 text-stone-300 transition group-hover:translate-x-0.5 group-hover:text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </div>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-stone-900">{{ $totalClientes }}</p>
        <p class="mt-1 text-sm text-stone-500">Clientes cadastrados</p>
    </a>

    <a href="{{ route('pizzas.index') }}" class="group rounded-2xl border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-amber-300">
        <div class="flex items-start justify-between">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 ring-1 ring-red-100">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/><path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/></svg>
            </span>
            <svg class="h-4 w-4 text-stone-300 transition group-hover:translate-x-0.5 group-hover:text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </div>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-stone-900">{{ $totalPizzas }}</p>
        <p class="mt-1 text-sm text-stone-500">Sabores disponíveis</p>
    </a>

    <a href="{{ route('pedidos.index') }}" class="group rounded-2xl border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-amber-300">
        <div class="flex items-start justify-between">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/></svg>
            </span>
            <svg class="h-4 w-4 text-stone-300 transition group-hover:translate-x-0.5 group-hover:text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </div>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-stone-900">{{ $totalPedidos }}</p>
        <p class="mt-1 text-sm text-stone-500">Pedidos registrados</p>
    </a>

    <div class="rounded-2xl border border-stone-900 bg-stone-900 p-5 text-white shadow-sm">
        <div class="flex items-start justify-between">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-amber-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 6.5c0-2-2.2-3-5-3s-5 1-5 3 2 2.8 5 3.4 5 1.4 5 3.6-2.2 3-5 3-5-1-5-3"/></svg>
            </span>
            <span class="rounded-full bg-emerald-400/10 px-2 py-1 text-[10px] font-medium text-emerald-400 ring-1 ring-emerald-400/20">Faturamento</span>
        </div>
        <p class="mt-4 text-2xl font-semibold tracking-tight">R$ {{ number_format($faturamento, 2, ',', '.') }}</p>
        <p class="mt-1 text-sm text-stone-400">Total em pedidos ativos</p>
    </div>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-3">
    <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white xl:col-span-2">
        <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="text-sm font-semibold text-stone-900">Pedidos recentes</h2>
                <p class="mt-0.5 text-xs text-stone-500">Ordenados pelo ano da data do pedido</p>
            </div>
            <a href="{{ route('pedidos.index') }}" class="text-xs font-medium text-amber-700 hover:text-amber-800">Ver todos →</a>
        </div>

        @if ($pedidosRecentes->isEmpty())
            <div class="px-5 py-12 text-center">
                <p class="text-sm text-stone-500">Nenhum pedido registrado ainda.</p>
                <a href="{{ route('pedidos.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-amber-700">Registrar o primeiro pedido →</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-stone-50 text-[11px] uppercase tracking-wide text-stone-500">
                        <tr>
                            <th class="px-5 py-3 font-medium">Cliente</th>
                            <th class="px-5 py-3 font-medium">Pizza</th>
                            <th class="px-5 py-3 font-medium">Data</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($pedidosRecentes as $pedido)
                            <tr class="transition hover:bg-stone-50/70">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-stone-800">{{ $pedido->cliente?->nome }}</p>
                                    <p class="text-xs text-stone-400">Pedido #{{ $pedido->id }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-stone-700">{{ $pedido->pizza?->sabor ?? '—' }}</p>
                                    <p class="text-xs text-stone-400">{{ $pedido->quantidade }}x · {{ $pedido->pizza?->tamanho_descricao }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-stone-600">
                                    {{ $pedido->data_pedido?->format('d/m/Y') }}
                                    <span class="block text-xs text-stone-400">{{ $pedido->ano }}</span>
                                </td>
                                <td class="px-5 py-3.5"><x-status-pedido :status="$pedido->status" /></td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right font-medium text-stone-800">R$ {{ number_format((float) $pedido->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <div class="space-y-6">
        <section class="rounded-2xl border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-stone-900">Pedidos por ano</h2>
            <p class="mt-0.5 text-xs text-stone-500">Volume histórico da operação</p>

            <div class="mt-4 space-y-3">
                @forelse ($pedidosPorAno as $ano => $total)
                    @php $max = $pedidosPorAno->max() ?: 1; @endphp
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-medium text-stone-700">{{ $ano }}</span>
                            <span class="text-stone-500">{{ $total }} {{ $total == 1 ? 'pedido' : 'pedidos' }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-stone-100">
                            <div class="h-full rounded-full bg-amber-500" style="width: {{ max(8, round($total / $max * 100)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-stone-500">Sem dados para exibir.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-amber-200 bg-amber-50/70 p-5">
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500/15 text-amber-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v5c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-amber-900">Sessão protegida</p>
                    <p class="mt-1 text-xs leading-relaxed text-amber-800/80">
                        Tempo limite de {{ $sessaoExpiraEm }} minutos por inatividade. O CPF dos clientes é
                        armazenado com criptografia AES-256 e hash de verificação.
                    </p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
