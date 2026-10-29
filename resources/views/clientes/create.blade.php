@extends('layouts.app')

@section('titulo', 'Novo cliente')
@section('subtitulo', 'Preencha os dados do cliente. O CPF será validado e armazenado de forma criptografada.')

@section('acoes')
    <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm transition hover:bg-stone-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Voltar para a lista
    </a>
@endsection

@section('conteudo')
    @include('clientes._form', ['cliente' => new \App\Models\Cliente])
@endsection
