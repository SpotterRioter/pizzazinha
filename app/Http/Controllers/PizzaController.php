<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PizzaController extends Controller
{
    public function index(Request $request): View
    {
        $termo = trim((string) $request->query('busca', ''));

        $pizzas = Pizza::query()
            ->when($termo, function ($query) use ($termo) {
                $query->where(function ($q) use ($termo) {
                    $q->where('sabor', 'like', "%{$termo}%")
                        ->orWhere('ingredientes', 'like', "%{$termo}%");
                });
            })
            ->withCount('pedidos')
            ->orderBy('sabor')
            ->orderBy('tamanho')
            ->paginate(12)
            ->withQueryString();

        return view('pizzas.index', compact('pizzas', 'termo'));
    }

    public function create(): View
    {
        return view('pizzas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Pizza::create($this->validar($request));

        return redirect()
            ->route('pizzas.index')
            ->with('sucesso', 'Pizza adicionada ao cardápio com sucesso.');
    }

    public function edit(Pizza $pizza): View
    {
        return view('pizzas.edit', compact('pizza'));
    }

    public function update(Request $request, Pizza $pizza): RedirectResponse
    {
        $pizza->update($this->validar($request));

        return redirect()
            ->route('pizzas.index')
            ->with('sucesso', 'Pizza "'.$pizza->sabor.'" atualizada com sucesso.');
    }

    public function destroy(Pizza $pizza): RedirectResponse
    {
        $sabor = $pizza->sabor;
        $pizza->delete();

        return redirect()
            ->route('pizzas.index')
            ->with('sucesso', 'Pizza "'.$sabor.'" removida do cardápio.');
    }

    public function alternarDisponibilidade(Pizza $pizza): RedirectResponse
    {
        $pizza->update(['disponivel' => ! $pizza->disponivel]);

        return redirect()
            ->route('pizzas.index')
            ->with('sucesso', 'Pizza "'.$pizza->sabor.'" marcada como '.($pizza->disponivel ? 'disponível' : 'indisponível').'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'sabor' => ['required', 'string', 'max:120'],
            'ingredientes' => ['required', 'string', 'max:500'],
            'tamanho' => ['required', Rule::in(array_keys(Pizza::TAMANHOS))],
            'preco' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'disponivel' => ['nullable', 'boolean'],
            'personalizada' => ['nullable', 'boolean'],
        ], [
            'sabor.required' => 'Informe o sabor da pizza.',
            'ingredientes.required' => 'Descreva os ingredientes da pizza.',
            'tamanho.required' => 'Selecione o tamanho.',
            'preco.required' => 'Informe o preço da pizza.',
            'preco.numeric' => 'O preço deve ser um valor numérico.',
        ]);

        $dados['disponivel'] = $request->boolean('disponivel');
        $dados['personalizada'] = $request->boolean('personalizada');

        return $dados;
    }
}
