@extends('layouts.app')

@section('titulo', 'Editar pedido #'.str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT))
@section('subtitulo', 'Atualize a data do pedido, a data de entrega e demais dados da comanda. As alterações são salvas no banco.')

@section('acoes')
    <a href="{{ route('pedidos.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm transition hover:bg-stone-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Voltar para os pedidos
    </a>
@endsection

@section('conteudo')
    <div class="mb-5 flex flex-wrap items-center gap-4 rounded-2xl border border-stone-200 bg-white px-5 py-4">
        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-stone-900 font-semibold text-white">
            #{{ str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT) }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="font-medium text-stone-800">{{ $pedido->cliente?->nome }}</p>
            <p class="text-xs text-stone-500">{{ $pedido->pizza?->sabor }} · {{ $pedido->quantidade }}x · Ano {{ $pedido->ano }}</p>
        </div>
        <x-status-pedido :status="$pedido->status" />
        <div class="text-right">
            <p class="text-[11px] uppercase tracking-wide text-stone-400">Total</p>
            <p class="text-sm font-semibold text-stone-800">R$ {{ number_format((float) $pedido->valor_total, 2, ',', '.') }}</p>
        </div>
    </div>

    @include('pedidos._form', ['pedido' => $pedido])
@endsection
