@extends('layouts.app')

@section('titulo', 'Clientes')
@section('subtitulo', 'Cadastre, busque e edite os clientes da pizzaria. O CPF é criptografado antes de ser gravado no banco.')

@section('acoes')
    <a href="{{ route('clientes.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Novo cliente
    </a>
@endsection

@section('conteudo')
<div class="rounded-2xl border border-stone-200 bg-white">
    <div class="flex flex-col gap-3 border-b border-stone-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-sm font-semibold text-stone-900">Lista de clientes</h2>
            <p class="mt-0.5 text-xs text-stone-500">
                {{ $clientes->total() }} {{ $clientes->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                @if ($termo)
                    para “{{ $termo }}”
                @endif
            </p>
        </div>

        <form method="GET" action="{{ route('clientes.index') }}" class="flex w-full gap-2 sm:w-auto">
            <div class="relative flex-1 sm:w-72">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="search" name="busca" value="{{ $termo }}" placeholder="Buscar por nome, CPF, e-mail…"
                       class="w-full rounded-xl border border-stone-300 bg-white py-2.5 pl-9 pr-3 text-sm text-stone-800 placeholder-stone-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
            </div>
            <button type="submit" class="rounded-xl border border-stone-300 bg-stone-50 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-stone-100">Buscar</button>
            @if ($termo)
                <a href="{{ route('clientes.index') }}" class="flex items-center rounded-xl px-3 text-sm text-stone-500 transition hover:text-stone-800" title="Limpar busca">Limpar</a>
            @endif
        </form>
    </div>

    @if ($clientes->isEmpty())
        <div class="px-5 py-16 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-stone-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </span>
            <p class="mt-3 text-sm font-medium text-stone-700">Nenhum cliente encontrado</p>
            <p class="mt-1 text-xs text-stone-500">
                {{ $termo ? 'Tente outro termo de busca ou cadastre um novo cliente.' : 'Cadastre o primeiro cliente para começar.' }}
            </p>
            <a href="{{ route('clientes.create') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-amber-700 hover:text-amber-800">Cadastrar cliente →</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-[11px] uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Cliente</th>
                        <th class="px-4 py-3 font-medium">CPF (LGPD)</th>
                        <th class="px-4 py-3 font-medium">Contato</th>
                        <th class="px-4 py-3 font-medium">Cidade</th>
                        <th class="px-4 py-3 text-right font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach ($clientes as $cliente)
                        <tr class="transition hover:bg-stone-50/70">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-50 text-xs font-semibold text-amber-700 ring-1 ring-amber-100">
                                        {{ mb_strtoupper(mb_substr($cliente->nome, 0, 2)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="flex items-center gap-2 truncate font-medium text-stone-800">
                                            {{ $cliente->nome }}
                                            <span class="inline-flex shrink-0 items-center rounded-full bg-stone-100 px-2 py-0.5 text-[10px] font-medium text-stone-500" title="Pedidos do cliente">{{ $cliente->pedidos_count }} ped.</span>
                                        </p>
                                        <p class="truncate text-xs text-stone-400">#{{ $cliente->id }} · {{ $cliente->email ?? 'sem e-mail' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5">
                                <span class="font-mono text-xs text-stone-600">{{ $cliente->cpf_mascarado }}</span>
                                <span class="mt-0.5 flex items-center gap-1 text-[10px] text-emerald-600">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                                    criptografado
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-stone-700">{{ $cliente->telefone }}</p>
                                <p class="text-xs text-stone-400">{{ $cliente->email ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-stone-600">
                                {{ $cliente->cidade ?? '—' }}
                                @if ($cliente->uf)
                                    <span class="text-xs text-stone-400">/{{ $cliente->uf }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('clientes.edit', $cliente) }}"
                                       class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800">Editar</a>
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('Remover o cliente {{ addslashes($cliente->nome) }}? Os pedidos vinculados também serão excluídos.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-transparent px-2.5 py-1.5 text-xs font-medium text-stone-400 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($clientes->hasPages())
            <div class="border-t border-stone-100 px-5 py-4">
                {{ $clientes->links() }}
            </div>
        @endif
    @endif
</div>

<div class="mt-4 flex items-start gap-2 rounded-2xl border border-stone-200 bg-white/60 px-4 py-3 text-xs text-stone-500">
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v5c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
    <p><strong class="font-medium text-stone-700">Conformidade LGPD:</strong> o CPF é cifrado com AES-256 (chave da aplicação) e um hash HMAC-SHA256 é usado apenas para busca e prevenção de duplicidade. A listagem exibe o CPF mascarado.</p>
</div>
@endsection
