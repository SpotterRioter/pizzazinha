<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Rules\Cpf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $termo = trim((string) $request->query('busca', ''));

        $clientes = Cliente::query()
            ->busca($termo)
            ->withCount('pedidos')
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'termo'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);

        Cliente::create($dados);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Cliente "'.$dados['nome'].'" cadastrado com sucesso. O CPF foi gravado de forma criptografada.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $dados = $this->validar($request, $cliente->id);

        $cliente->update($dados);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Dados de "'.$cliente->nome.'" atualizados com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $nome = $cliente->nome;
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Cliente "'.$nome.'" removido. Os pedidos vinculados também foram excluídos.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?int $ignorarId = null): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'cpf' => ['required', 'string', 'max:20', new Cpf],
            'email' => ['nullable', 'email', 'max:150'],
            'telefone' => ['required', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:180'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', 'string', 'size:2'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ], [
            'nome.required' => 'Informe o nome do cliente.',
            'cpf.required' => 'Informe o CPF do cliente.',
            'telefone.required' => 'Informe o telefone de contato.',
            'email.email' => 'Digite um e-mail válido.',
            'uf.size' => 'A UF deve ter exatamente 2 letras.',
        ]);

        $request->merge(['uf' => isset($dados['uf']) ? strtoupper($dados['uf']) : null]);

        $dados = $request->only([
            'nome', 'cpf', 'email', 'telefone', 'endereco', 'cidade', 'uf', 'observacoes',
        ]);

        $dados['uf'] = $dados['uf'] ?? null;

        $hash = Cliente::hashCpf($dados['cpf']);

        $duplicado = Cliente::where('cpf_hash', $hash)
            ->when($ignorarId, fn ($q) => $q->whereKeyNot($ignorarId))
            ->exists();

        if ($duplicado) {
            throw ValidationException::withMessages([
                'cpf' => 'Já existe um cliente cadastrado com este CPF.',
            ]);
        }

        return $dados;
    }
}
