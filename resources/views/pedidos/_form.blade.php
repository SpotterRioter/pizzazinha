{{-- Formulário compartilhado de pedido --}}

<form method="POST" action="{{ $pedido->exists ? route('pedidos.update', $pedido) : route('pedidos.store') }}" class="space-y-6">
    @csrf
    @if ($pedido->exists)
        @method('PUT')
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="space-y-6 xl:col-span-2">
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-stone-900">Cliente e pizza</h2>
                <p class="mt-0.5 text-xs text-stone-500">Associe o pedido ao cliente e ao sabor cadastrados no banco.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="cliente_id" class="mb-1.5 block text-xs font-medium text-stone-600">Cliente <span class="text-red-500">*</span></label>
                        <select id="cliente_id" name="cliente_id" required
                                class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            <option value="">Selecione o cliente…</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" @selected((string) old('cliente_id', $pedido->cliente_id ?? request('cliente_id')) === (string) $cliente->id)>
                                    {{ $cliente->nome }} · {{ $cliente->telefone }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        @if ($clientes->isEmpty())
                            <p class="mt-1.5 text-xs text-amber-700">Nenhum cliente cadastrado. <a href="{{ route('clientes.create') }}" class="font-medium underline">Cadastre um cliente primeiro.</a></p>
                        @endif
                    </div>

                    <div>
                        <label for="pizza_id" class="mb-1.5 block text-xs font-medium text-stone-600">Pizza <span class="text-red-500">*</span></label>
                        <select id="pizza_id" name="pizza_id" required data-pizza-select
                                class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            <option value="">Selecione o sabor…</option>
                            @foreach ($pizzas as $pizza)
                                <option value="{{ $pizza->id }}" data-preco="{{ (float) $pizza->preco }}" @selected((string) old('pizza_id', $pedido->pizza_id) === (string) $pizza->id)>
                                    {{ $pizza->sabor }} ({{ $pizza->tamanho }}) — R$ {{ number_format((float) $pizza->preco, 2, ',', '.') }}{{ $pizza->disponivel ? '' : ' · indisponível' }}
                                </option>
                            @endforeach
                        </select>
                        @error('pizza_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <a href="{{ route('pizzas.create') }}" class="mt-1.5 inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 hover:text-amber-800">+ Cadastrar item personalizado</a>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="quantidade" class="mb-1.5 block text-xs font-medium text-stone-600">Quantidade <span class="text-red-500">*</span></label>
                            <input id="quantidade" name="quantidade" type="number" min="1" max="99" required data-quantidade
                                   value="{{ old('quantidade', $pedido->quantidade ?? 1) }}"
                                   class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            @error('quantidade') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="mb-1.5 block text-xs font-medium text-stone-600">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                    class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                                @foreach ($status as $valor => $rotulo)
                                    <option value="{{ $valor }}" @selected(old('status', $pedido->status ?? 'pendente') === $valor)>{{ $rotulo }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-stone-900">Datas e observações</h2>
                <p class="mt-0.5 text-xs text-stone-500">A data do pedido define o ano usado na ordenação da listagem.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="data_pedido" class="mb-1.5 block text-xs font-medium text-stone-600">Data do pedido <span class="text-red-500">*</span></label>
                        <input id="data_pedido" name="data_pedido" type="date" required
                               value="{{ old('data_pedido', $pedido->data_pedido?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        @error('data_pedido') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="data_entrega" class="mb-1.5 block text-xs font-medium text-stone-600">Data de entrega / agendamento</label>
                        <input id="data_entrega" name="data_entrega" type="date"
                               value="{{ old('data_entrega', $pedido->data_entrega?->format('Y-m-d')) }}"
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        @error('data_entrega') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="observacoes" class="mb-1.5 block text-xs font-medium text-stone-600">Observações da comanda</label>
                        <textarea id="observacoes" name="observacoes" rows="3" maxlength="1000" placeholder="Sem cebola, borda recheada, ponto da massa…"
                                  class="w-full resize-none rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">{{ old('observacoes', $pedido->observacoes) }}</textarea>
                        @error('observacoes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </section>

        <aside class="space-y-4">
            <div class="rounded-2xl bg-stone-900 p-5 text-white shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.18em] text-stone-400">Resumo do pedido</p>
                <div class="mt-4 flex items-end justify-between">
                    <span class="text-sm text-stone-300">Total estimado</span>
                    <span class="text-2xl font-semibold tracking-tight text-amber-400" data-total-preview>
                        R$ {{ number_format(old('valor_total', (float) ($pedido->valor_total ?? 0)), 2, ',', '.') }}
                    </span>
                </div>
                <div class="mt-4 space-y-2 border-t border-white/10 pt-4 text-xs text-stone-400">
                    <p class="flex justify-between"><span>Preço unitário</span><span data-preco-preview class="font-medium text-stone-200">—</span></p>
                    <p class="flex justify-between"><span>Quantidade</span><span data-quantidade-preview class="font-medium text-stone-200">{{ old('quantidade', $pedido->quantidade ?? 1) }}</span></p>
                </div>
                <p class="mt-4 text-[11px] leading-relaxed text-stone-500">O valor total é calculado pelo sistema a partir do preço vigente da pizza.</p>
            </div>

            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-stone-900">Atenção</h2>
                <ul class="mt-3 space-y-2 text-xs leading-relaxed text-stone-500">
                    <li class="flex gap-2"><span class="text-amber-500">•</span> O pedido aparece na listagem agrupado pelo ano da data informada.</li>
                    <li class="flex gap-2"><span class="text-amber-500">•</span> A data de entrega não pode ser anterior à data do pedido.</li>
                    <li class="flex gap-2"><span class="text-amber-500">•</span> Alterações de data podem ser feitas depois pela tela de edição.</li>
                </ul>
            </div>
        </aside>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2">
        <a href="{{ route('pedidos.index') }}"
           class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50">Cancelar</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M8 3v5h7V3M8 21v-7h8v7"/></svg>
            {{ $pedido->exists ? 'Salvar alterações' : 'Registrar pedido' }}
        </button>
    </div>
</form>

<script>
    (function () {
        const select = document.querySelector('[data-pizza-select]');
        const quantidade = document.querySelector('[data-quantidade]');
        const precoPreview = document.querySelector('[data-preco-preview]');
        const quantidadePreview = document.querySelector('[data-quantidade-preview]');
        const totalPreview = document.querySelector('[data-total-preview]');

        if (!select || !quantidade || !totalPreview) return;

        const formata = (valor) => 'R$ ' + valor.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        const atualizar = () => {
            const opcao = select.options[select.selectedIndex];
            const preco = parseFloat(opcao?.dataset?.preco || 0);
            const qtd = Math.max(1, parseInt(quantidade.value || 1, 10));

            precoPreview.textContent = opcao?.dataset?.preco ? formata(preco) : '—';
            quantidadePreview.textContent = qtd;
            totalPreview.textContent = formata(preco * qtd);
        };

        select.addEventListener('change', atualizar);
        quantidade.addEventListener('input', atualizar);
        atualizar();
    })();
</script>
