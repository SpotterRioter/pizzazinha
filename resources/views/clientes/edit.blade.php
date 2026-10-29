@extends('layouts.app')

@section('titulo', 'Editar cliente')
@section('subtitulo', 'Atualize os dados de '.$cliente->nome.'. As alterações são salvas diretamente no banco de dados.')

@section('acoes')
    <a href="{{ route('pedidos.create', ['cliente_id' => $cliente->id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 shadow-sm transition hover:bg-stone-50">
        Novo pedido para este cliente
    </a>
    <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm transition hover:bg-stone-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Voltar
    </a>
@endsection

@section('conteudo')
    <div class="mb-5 flex flex-wrap items-center gap-4 rounded-2xl border border-stone-200 bg-white px-5 py-4">
        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-sm font-semibold text-amber-700 ring-1 ring-amber-100">
            {{ mb_strtoupper(mb_substr($cliente->nome, 0, 2)) }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="font-medium text-stone-800">{{ $cliente->nome }}</p>
            <p class="text-xs text-stone-500">Cliente #{{ $cliente->id }} · {{ $cliente->pedidos()->count() }} pedido(s) · CPF {{ $cliente->cpf_mascarado }}</p>
        </div>
        <div class="text-right">
            <p class="text-[11px] uppercase tracking-wide text-stone-400">Total em pedidos</p>
            <p class="text-sm font-semibold text-stone-800">R$ {{ number_format($cliente->total_gasto, 2, ',', '.') }}</p>
        </div>
    </div>

    @include('clientes._form', ['cliente' => $cliente])
@endsection
