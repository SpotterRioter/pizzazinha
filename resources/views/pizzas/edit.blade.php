@extends('layouts.app')

@section('titulo', 'Editar pizza')
@section('subtitulo', 'Atualize os dados de "'.$pizza->sabor.'". As alterações são salvas no cardápio.')

@section('acoes')
    <a href="{{ route('pizzas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm transition hover:bg-stone-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Voltar para o cardápio
    </a>
@endsection

@section('conteudo')
    <div class="mb-5 flex flex-wrap items-center gap-4 rounded-2xl border border-stone-200 bg-white px-5 py-4">
        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/><path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <p class="font-medium text-stone-800">{{ $pizza->sabor }}</p>
            <p class="text-xs text-stone-500">Pizza #{{ $pizza->id }} · {{ $pizza->tamanho_descricao }} · {{ $pizza->pedidos()->count() }} pedido(s)</p>
        </div>
        <div class="text-right">
            <p class="text-[11px] uppercase tracking-wide text-stone-400">Preço atual</p>
            <p class="text-sm font-semibold text-stone-800">R$ {{ number_format((float) $pizza->preco, 2, ',', '.') }}</p>
        </div>
    </div>

    @include('pizzas._form', ['pizza' => $pizza])
@endsection
