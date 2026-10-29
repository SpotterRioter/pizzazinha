<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Pizza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $termo = trim((string) $request->query('busca', ''));
        $ano = $request->query('ano');
        $status = $request->query('status');

        $pedidos = Pedido::query()
            ->with(['cliente', 'pizza'])
            ->when($termo, function ($query) use ($termo) {
                $query->whereHas('cliente', function ($q) use ($termo) {
                    $q->where('nome', 'like', "%{$termo}%")
                        ->orWhere('email', 'like', "%{$termo}%")
                        ->orWhere('telefone', 'like', "%{$termo}%");
                });
            })
            ->when($ano, function ($query) use ($ano) {
                $query->whereYear('data_pedido', $ano);
            })
            ->when($status && array_key_exists($status, Pedido::STATUS), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->ordenadoPorAno()
            ->paginate(12)
            ->withQueryString();

        $anos = \Illuminate\Support\Facades\DB::table('pedidos')
            ->selectRaw('distinct substr(data_pedido, 1, 4) as ano')
            ->orderByDesc('ano')
            ->pluck('ano');

        return view('pedidos.index', compact('pedidos', 'termo', 'ano', 'status', 'anos'));
    }

    public function create(): View
    {
        return view('pedidos.create', [
            'clientes' => Cliente::orderBy('nome')->get(),
            'pizzas' => Pizza::orderBy('sabor')->get(),
            'status' => Pedido::STATUS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);
        $dados['valor_total'] = $this->calcularTotal($dados['pizza_id'], $dados['quantidade']);

        $pedido = Pedido::create($dados);

        return redirect()
            ->route('pedidos.index')
            ->with('sucesso', "Pedido #{$pedido->id} registrado para {$pedido->cliente->nome}.");
    }

    public function edit(Pedido $pedido): View
    {
        return view('pedidos.edit', [
            'pedido' => $pedido,
            'clientes' => Cliente::orderBy('nome')->get(),
            'pizzas' => Pizza::orderBy('sabor')->get(),
            'status' => Pedido::STATUS,
        ]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $dados = $this->validar($request);
        $dados['valor_total'] = $this->calcularTotal($dados['pizza_id'], $dados['quantidade']);

        $pedido->update($dados);

        return redirect()
            ->route('pedidos.index')
            ->with('sucesso', "Pedido #{$pedido->id} atualizado. Datas e valores foram salvos no banco de dados.");
    }

    public function destroy(Pedido $pedido): RedirectResponse
    {
        $id = $pedido->id;
        $pedido->delete();

        return redirect()
            ->route('pedidos.index')
            ->with('sucesso', "Pedido #{$id} excluído.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'pizza_id' => ['required', 'exists:pizzas,id'],
            'quantidade' => ['required', 'integer', 'min:1', 'max:99'],
            'status' => ['required', Rule::in(array_keys(Pedido::STATUS))],
            'data_pedido' => ['required', 'date'],
            'data_entrega' => ['nullable', 'date', 'after_or_equal:data_pedido'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ], [
            'cliente_id.required' => 'Selecione o cliente do pedido.',
            'cliente_id.exists' => 'O cliente selecionado não existe.',
            'pizza_id.required' => 'Selecione a pizza do pedido.',
            'pizza_id.exists' => 'A pizza selecionada não existe.',
            'quantidade.required' => 'Informe a quantidade.',
            'quantidade.min' => 'A quantidade mínima é 1.',
            'status.required' => 'Selecione o status do pedido.',
            'data_pedido.required' => 'Informe a data do pedido.',
            'data_entrega.after_or_equal' => 'A data de entrega não pode ser anterior à data do pedido.',
        ]);
    }

    private function calcularTotal(int $pizzaId, int $quantidade): float
    {
        $preco = (float) Pizza::whereKey($pizzaId)->value('preco');

        return round($preco * $quantidade, 2);
    }
}
