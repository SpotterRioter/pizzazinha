<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $totalClientes = Cliente::count();
        $totalPizzas = Pizza::where('disponivel', true)->count();
        $totalPedidos = Pedido::count();
        $faturamento = (float) Pedido::where('status', '!=', 'cancelado')->sum('valor_total');

        $pedidosRecentes = Pedido::with(['cliente', 'pizza'])
            ->ordenadoPorAno()
            ->limit(5)
            ->get();

        $anoExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y', data_pedido)"
            : 'YEAR(data_pedido)';

        $pedidosPorAno = DB::table('pedidos')
            ->select(DB::raw("{$anoExpr} as ano"), DB::raw('COUNT(*) as total'))
            ->groupBy('ano')
            ->orderByDesc('ano')
            ->pluck('total', 'ano');

        $sessaoExpiraEm = (int) config('session.lifetime');
        $loginAt = $request->session()->get('login_at');

        return view('dashboard', compact(
            'totalClientes',
            'totalPizzas',
            'totalPedidos',
            'faturamento',
            'pedidosRecentes',
            'pedidosPorAno',
            'sessaoExpiraEm',
            'loginAt',
        ));
    }
}
