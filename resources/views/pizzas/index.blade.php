@extends('layouts.app')

@section('titulo', 'Cardápio de pizzas')
@section('subtitulo', 'Cadastre sabores, ingredientes, tamanhos e preços. Itens personalizados também podem ser vinculados aos pedidos.')

@section('acoes')
    <a href="{{ route('pizzas.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Nova pizza
    </a>
@endsection

@section('conteudo')
<div class="mb-5 flex flex-col gap-3 rounded-2xl border border-stone-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-sm font-semibold text-stone-900">Sabores cadastrados</h2>
        <p class="mt-0.5 text-xs text-stone-500">
            {{ $pizzas->total() }} {{ $pizzas->total() === 1 ? 'sabor no cardápio' : 'sabores no cardápio' }}
            @if ($termo) para “{{ $termo }}” @endif
        </p>
    </div>

    <form method="GET" action="{{ route('pizzas.index') }}" class="flex w-full gap-2 sm:w-auto">
        <div class="relative flex-1 sm:w-64">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" name="busca" value="{{ $termo }}" placeholder="Buscar sabor ou ingrediente…"
                   class="w-full rounded-xl border border-stone-300 bg-white py-2.5 pl-9 pr-3 text-sm text-stone-800 placeholder-stone-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
        </div>
        <button type="submit" class="rounded-xl border border-stone-300 bg-stone-50 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-stone-100">Buscar</button>
        @if ($termo)
            <a href="{{ route('pizzas.index') }}" class="flex items-center rounded-xl px-3 text-sm text-stone-500 transition hover:text-stone-800">Limpar</a>
        @endif
    </form>
</div>

@if ($pizzas->isEmpty())
    <div class="rounded-2xl border border-stone-200 bg-white px-5 py-16 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-stone-400">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/><path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/></svg>
        </span>
        <p class="mt-3 text-sm font-medium text-stone-700">Nenhuma pizza encontrada</p>
        <p class="mt-1 text-xs text-stone-500">Cadastre um sabor para começar a montar o cardápio.</p>
        <a href="{{ route('pizzas.create') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-amber-700 hover:text-amber-800">Cadastrar pizza →</a>
    </div>
@else
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($pizzas as $pizza)
            <article class="group flex flex-col overflow-hidden rounded-2xl border border-stone-200 bg-white transition hover:-translate-y-0.5 {{ $pizza->disponivel ? '' : 'opacity-75' }}">
                <div class="relative flex h-28 items-center justify-center bg-gradient-to-br from-stone-900 via-stone-800 to-stone-900">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 30%, #f59e0b 0 2px, transparent 2px), radial-gradient(circle at 70% 60%, #f59e0b 0 2px, transparent 2px), radial-gradient(circle at 45% 80%, #ef4444 0 2px, transparent 2px); background-size: 42px 42px;"></div>
                    <svg class="relative h-14 w-14 text-amber-400 drop-shadow-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m2 16 20 6-6-20A20 20 0 0 0 2 16" fill="currentColor" fill-opacity="0.12"/>
                        <path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/>
                        <path d="M15 11h.01"/>
                        <path d="M11 15h.01"/>
                        <path d="M16 16h.01"/>
                    </svg>
                    <div class="absolute left-3 top-3 flex gap-1.5">
                        <span class="rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white backdrop-blur">{{ $pizza->tamanho }}</span>
                        @if ($pizza->personalizada)
                            <span class="rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-semibold text-stone-950">Personalizada</span>
                        @endif
                    </div>
                    <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-medium backdrop-blur {{ $pizza->disponivel ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $pizza->disponivel ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $pizza->disponivel ? 'Disponível' : 'Indisponível' }}
                    </span>
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="font-semibold leading-snug text-stone-900">{{ $pizza->sabor }}</h3>
                        <p class="shrink-0 text-right">
                            <span class="block text-lg font-semibold tracking-tight text-stone-900">R$ {{ number_format((float) $pizza->preco, 2, ',', '.') }}</span>
                            <span class="text-[10px] uppercase tracking-wide text-stone-400">por unidade</span>
                        </p>
                    </div>
                    <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-stone-500">{{ $pizza->ingredientes }}</p>

                    <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3 text-xs text-stone-500">
                        <span>{{ $pizza->tamanho_descricao }}</span>
                        <span>{{ $pizza->pedidos_count }} {{ $pizza->pedidos_count === 1 ? 'pedido' : 'pedidos' }}</span>
                    </div>

                    <div class="mt-3 flex items-center gap-1.5">
                        <a href="{{ route('pizzas.edit', $pizza) }}" class="flex-1 rounded-lg border border-stone-200 px-2.5 py-1.5 text-center text-xs font-medium text-stone-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800">Editar</a>
                        <form method="POST" action="{{ route('pizzas.disponibilidade', $pizza) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-500 transition hover:bg-stone-50" title="{{ $pizza->disponivel ? 'Marcar como indisponível' : 'Marcar como disponível' }}">
                                {{ $pizza->disponivel ? 'Pausar' : 'Ativar' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('pizzas.destroy', $pizza) }}" onsubmit="return confirm('Remover a pizza {{ addslashes($pizza->sabor) }} do cardápio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-transparent px-2.5 py-1.5 text-xs font-medium text-stone-400 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">Excluir</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    @if ($pizzas->hasPages())
        <div class="mt-5">{{ $pizzas->links() }}</div>
    @endif
@endif
@endsection
