@extends('layouts.app')

@section('titulo', 'Pedidos')
@section('subtitulo', 'Acompanhe as comandas agrupadas pelo ano da data do pedido, com os dados completos do cliente e da pizza.')

@section('acoes')
    <a href="{{ route('pedidos.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Novo pedido
    </a>
@endsection

@section('conteudo')
<div class="rounded-2xl border border-stone-200 bg-white">
    <div class="border-b border-stone-100 px-5 py-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-stone-900">Listagem de pedidos</h2>
                <p class="mt-0.5 text-xs text-stone-500">
                    {{ $pedidos->total() }} {{ $pedidos->total() === 1 ? 'pedido encontrado' : 'pedidos encontrados' }} · ordenados pelo ano (mais recente primeiro)
                </p>
            </div>

            <form method="GET" action="{{ route('pedidos.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input type="search" name="busca" value="{{ $termo }}" placeholder="Buscar cliente…"
                           class="w-44 rounded-xl border border-stone-300 bg-white py-2.5 pl-9 pr-3 text-sm text-stone-800 placeholder-stone-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                </div>

                <select name="ano" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-700 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    <option value="">Todos os anos</option>
                    @foreach ($anos as $opcaoAno)
                        <option value="{{ $opcaoAno }}" @selected((string) $ano === (string) $opcaoAno)>{{ $opcaoAno }}</option>
                    @endforeach
                </select>

                <select name="status" class="rounded-xl border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-700 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    <option value="">Todos os status</option>
                    @foreach (\App\Models\Pedido::STATUS as $valor => $rotulo)
                        <option value="{{ $valor }}" @selected($status === $valor)>{{ $rotulo }}</option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-xl border border-stone-300 bg-stone-50 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-stone-100">Filtrar</button>
                @if ($termo || $ano || $status)
                    <a href="{{ route('pedidos.index') }}" class="rounded-xl px-2 py-2.5 text-sm text-stone-500 transition hover:text-stone-800">Limpar</a>
                @endif
            </form>
        </div>
    </div>

    @if ($pedidos->isEmpty())
        <div class="px-5 py-16 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-stone-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/></svg>
            </span>
            <p class="mt-3 text-sm font-medium text-stone-700">Nenhum pedido encontrado</p>
            <p class="mt-1 text-xs text-stone-500">Ajuste os filtros ou registre um novo pedido.</p>
            <a href="{{ route('pedidos.create') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-amber-700 hover:text-amber-800">Registrar pedido →</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-[11px] uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Pedido</th>
                        <th class="px-5 py-3 font-medium">Cliente</th>
                        <th class="px-5 py-3 font-medium">Pizza</th>
                        <th class="px-5 py-3 font-medium">Datas</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 text-right font-medium">Total</th>
                        <th class="px-5 py-3 text-right font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @php $anoCorrente = null; @endphp
                    @foreach ($pedidos as $pedido)
                        @if ($pedido->ano !== $anoCorrente)
                            @php $anoCorrente = $pedido->ano; @endphp
                            <tr class="bg-stone-100/80">
                                <td colspan="7" class="px-5 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-6 min-w-14 items-center justify-center rounded-md bg-stone-900 px-2 text-[11px] font-semibold tracking-wide text-white">{{ $anoCorrente }}</span>
                                        <span class="text-[11px] font-medium uppercase tracking-wide text-stone-500">
                                            {{ $pedidos->where('ano', $anoCorrente)->count() }} pedido(s) neste ano
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        <tr class="align-top transition hover:bg-stone-50/70">
                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="font-semibold text-stone-800">#{{ str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-xs text-stone-400">{{ $pedido->created_at?->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium text-stone-800">{{ $pedido->cliente?->nome ?? 'Cliente removido' }}</p>
                                <p class="mt-0.5 text-xs text-stone-500">{{ $pedido->cliente?->telefone }}</p>
                                <p class="text-[11px] text-stone-400">CPF {{ $pedido->cliente?->cpf_mascarado }}</p>
                                @if ($pedido->cliente?->endereco)
                                    <p class="mt-0.5 max-w-52 truncate text-[11px] text-stone-400" title="{{ $pedido->cliente->endereco }}">{{ $pedido->cliente->endereco }}{{ $pedido->cliente->cidade ? ' · '.$pedido->cliente->cidade : '' }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium text-stone-800">{{ $pedido->pizza?->sabor ?? 'Item removido' }}</p>
                                <p class="mt-0.5 text-xs text-stone-500">{{ $pedido->quantidade }}x · {{ $pedido->pizza?->tamanho_descricao }}</p>
                                @if ($pedido->pizza?->ingredientes)
                                    <p class="mt-0.5 max-w-56 text-[11px] leading-snug text-stone-400 line-clamp-2" title="{{ $pedido->pizza->ingredientes }}">{{ $pedido->pizza->ingredientes }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs">
                                <p class="text-stone-700"><span class="text-stone-400">Pedido:</span> {{ $pedido->data_pedido?->format('d/m/Y') }}</p>
                                <p class="mt-0.5 text-stone-700"><span class="text-stone-400">Entrega:</span> {{ $pedido->data_entrega?->format('d/m/Y') ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-4"><x-status-pedido :status="$pedido->status" /></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <p class="font-semibold text-stone-800">R$ {{ number_format((float) $pedido->valor_total, 2, ',', '.') }}</p>
                                <p class="text-[11px] text-stone-400">R$ {{ number_format((float) ($pedido->pizza?->preco ?? 0), 2, ',', '.') }} /un.</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('pedidos.edit', $pedido) }}"
                                       class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800">Editar</a>
                                    <form method="POST" action="{{ route('pedidos.destroy', $pedido) }}" onsubmit="return confirm('Excluir o pedido #{{ $pedido->id }}?');">
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

        @if ($pedidos->hasPages())
            <div class="border-t border-stone-100 px-5 py-4">
                {{ $pedidos->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
