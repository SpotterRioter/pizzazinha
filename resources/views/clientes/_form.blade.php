{{-- Formulário compartilhado de cliente --}}

<form method="POST" action="{{ $cliente->exists ? route('clientes.update', $cliente) : route('clientes.store') }}" class="space-y-6">
    @csrf
    @if ($cliente->exists)
        @method('PUT')
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="rounded-2xl border border-stone-200 bg-white p-5 xl:col-span-2">
            <h2 class="text-sm font-semibold text-stone-900">Dados pessoais</h2>
            <p class="mt-0.5 text-xs text-stone-500">Informações usadas na comanda e no programa de fidelidade.</p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nome" class="mb-1.5 block text-xs font-medium text-stone-600">Nome completo <span class="text-red-500">*</span></label>
                    <input id="nome" name="nome" type="text" required maxlength="120"
                           value="{{ old('nome', $cliente->nome) }}"
                           class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    @error('nome') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cpf" class="mb-1.5 block text-xs font-medium text-stone-600">CPF <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input id="cpf" name="cpf" type="text" required maxlength="14" inputmode="numeric"
                               value="{{ old('cpf', $cliente->cpf) }}" placeholder="000.000.000-00" data-mask-cpf
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 pr-11 font-mono text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600" title="Criptografado no banco">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        </span>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-400">Gravado com criptografia AES-256 · exibido mascarado</p>
                    @error('cpf') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telefone" class="mb-1.5 block text-xs font-medium text-stone-600">Telefone / WhatsApp <span class="text-red-500">*</span></label>
                    <input id="telefone" name="telefone" type="text" required maxlength="20"
                           value="{{ old('telefone', $cliente->telefone) }}" placeholder="(00) 00000-0000" data-mask-telefone
                           class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    @error('telefone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="mb-1.5 block text-xs font-medium text-stone-600">E-mail</label>
                    <input id="email" name="email" type="email" maxlength="150"
                           value="{{ old('email', $cliente->email) }}" placeholder="cliente@email.com"
                           class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-stone-900">Endereço de entrega</h2>
            <p class="mt-0.5 text-xs text-stone-500">Usado para agilizar novas entregas.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="endereco" class="mb-1.5 block text-xs font-medium text-stone-600">Endereço</label>
                    <input id="endereco" name="endereco" type="text" maxlength="180"
                           value="{{ old('endereco', $cliente->endereco) }}" placeholder="Rua, número, bairro"
                           class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    @error('endereco') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label for="cidade" class="mb-1.5 block text-xs font-medium text-stone-600">Cidade</label>
                        <input id="cidade" name="cidade" type="text" maxlength="100"
                               value="{{ old('cidade', $cliente->cidade) }}"
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        @error('cidade') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="uf" class="mb-1.5 block text-xs font-medium text-stone-600">UF</label>
                        <input id="uf" name="uf" type="text" maxlength="2" value="{{ old('uf', $cliente->uf) }}" placeholder="SP"
                               class="w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm uppercase text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                        @error('uf') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="observacoes" class="mb-1.5 block text-xs font-medium text-stone-600">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="3" maxlength="1000" placeholder="Preferências, restrições alimentares…"
                              class="w-full resize-none rounded-xl border border-stone-300 px-3.5 py-2.5 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">{{ old('observacoes', $cliente->observacoes) }}</textarea>
                    @error('observacoes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2">
        <a href="{{ route('clientes.index') }}"
           class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-50">Cancelar</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M8 3v5h7V3M8 21v-7h8v7"/></svg>
            {{ $cliente->exists ? 'Salvar alterações' : 'Cadastrar cliente' }}
        </button>
    </div>
</form>

<script>
    document.querySelectorAll('[data-mask-cpf]').forEach((input) => {
        input.addEventListener('input', () => {
            let v = input.value.replace(/\D/g, '').slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2')
                 .replace(/(\d{3})(\d)/, '$1.$2')
                 .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            input.value = v;
        });
    });

    document.querySelectorAll('[data-mask-telefone]').forEach((input) => {
        input.addEventListener('input', () => {
            let v = input.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10) {
                v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else {
                v = v.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
            }
            input.value = v;
        });
    });
</script>
