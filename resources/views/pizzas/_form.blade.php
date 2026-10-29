{{-- Formulário compartilhado de pizza --}}

<form method="POST" action="{{ $pizza->exists ? route('pizzas.update', $pizza) : route('pizzas.store') }}" class="space-y-6">
    @csrf
    @if ($pizza->exists)
        @method('PUT')
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="rounded-2xl border border-stone-200 bg-white p-5 xl:col-span-2">
            <h2 class="text-sm font-semibold text-stone-900">Dados da pizza</h2>
            <p class="mt-0.5 text-xs text-stone-500">Informe sabor, ingredientes, tamanho e preço de venda.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="sabor" class="mb-1.5 block text-xs font-medium text-stone-600">Sabor <span class="text-red-500">*</span></label>
                    <input id="sabor" name="sabor" type="text" required maxlength="120"
                           value="{{ old('sabor', $pizza->sabor) }}" placeholder="Ex.: Margherita Especial"
                           class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    @error('sabor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ingredientes" class="mb-1.5 block text-xs font-medium text-stone-600">Ingredientes <span class="text-red-500">*</span></label>
                    <textarea id="ingredientes" name="ingredientes" rows="4" required maxlength="500" placeholder="Molho artesanal, muçarela, manjericão fresco…"
                              class="w-full resize-none rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">{{ old('ingredientes', $pizza->ingredientes) }}</textarea>
                    @error('ingredientes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tamanho" class="mb-1.5 block text-xs font-medium text-stone-600">Tamanho <span class="text-red-500">*</span></label>
                        <select id="tamanho" name="tamanho" required
                                class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                            @foreach (\App\Models\Pizza::TAMANHOS as $valor => $rotulo)
                                <option value="{{ $valor }}" @selected(old('tamanho', $pizza->tamanho ?? 'M') === $valor)>{{ $rotulo }}</option>
                            @endforeach
                        </select>
                        @error('tamanho') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="preco" class="mb-1.5 block text-xs font-medium text-stone-600">Preço (R$) <span class="text-red-500">*</span></label>
                        <input id="preco" name="preco" type="number" step="0.01" min="0.01" required
                               value="{{ old('preco', $pizza->preco) }}" placeholder="0,00"
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        @error('preco') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-stone-900">Disponibilidade</h2>
            <p class="mt-0.5 text-xs text-stone-500">Controle a exibição e o uso no registro de pedidos.</p>

            <div class="mt-5 space-y-3">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 p-3.5 transition hover:bg-stone-50">
                    <input type="checkbox" name="disponivel" value="1" @checked(old('disponivel', $pizza->exists ? $pizza->disponivel : true))
                           class="mt-0.5 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                    <span>
                        <span class="block text-sm font-medium text-stone-800">Disponível no cardápio</span>
                        <span class="mt-0.5 block text-xs text-stone-500">Sabores pausados continuam salvos, mas aparecem como indisponíveis.</span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 p-3.5 transition hover:bg-stone-50">
                    <input type="checkbox" name="personalizada" value="1" @checked(old('personalizada', $pizza->personalizada))
                           class="mt-0.5 h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                    <span>
                        <span class="block text-sm font-medium text-stone-800">Item personalizado</span>
                        <span class="mt-0.5 block text-xs text-stone-500">Marque para combinações criadas sob medida para um cliente.</span>
                    </span>
                </label>
            </div>

            <div class="mt-5 rounded-xl bg-amber-50 p-3.5 text-xs leading-relaxed text-amber-900/80">
                <strong class="font-semibold">Dica:</strong> ao registrar um pedido, a pizza cadastrada aqui poderá ser associada ao cliente escolhido.
            </div>
        </section>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2">
        <a href="{{ route('pizzas.index') }}"
           class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50">Cancelar</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M8 3v5h7V3M8 21v-7h8v7"/></svg>
            {{ $pizza->exists ? 'Salvar alterações' : 'Adicionar ao cardápio' }}
        </button>
    </div>
</form>
