<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Pizza;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $senha = Hash::make('senha123');

        $usuarios = [
            ['name' => 'Ana Souza', 'email' => 'ana.souza@pizzaria.test', 'cargo' => 'Gerente'],
            ['name' => 'Bruno Lima', 'email' => 'bruno.lima@pizzaria.test', 'cargo' => 'Atendente'],
            ['name' => 'Carla Mendes', 'email' => 'carla.mendes@pizzaria.test', 'cargo' => 'Atendente'],
        ];

        foreach ($usuarios as $dados) {
            User::updateOrCreate(
                ['email' => $dados['email']],
                [
                    'name' => $dados['name'],
                    'cargo' => $dados['cargo'],
                    'ativo' => true,
                    'password' => $senha,
                ],
            );
        }

        $clientes = [
            [
                'nome' => 'Mariana Oliveira',
                'cpf' => '529.982.247-25',
                'email' => 'mariana.oliveira@email.com',
                'telefone' => '(11) 98888-1010',
                'endereco' => 'Rua das Oliveiras, 142 — Jardim Itália',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'observacoes' => 'Prefere borda fina e massa bem assada.',
            ],
            [
                'nome' => 'Rafael Pereira',
                'cpf' => '111.444.777-35',
                'email' => 'rafael.pereira@email.com',
                'telefone' => '(11) 97777-2020',
                'endereco' => 'Avenida Forno a Lenha, 890 — Centro',
                'cidade' => 'Campinas',
                'uf' => 'SP',
                'observacoes' => 'Cliente do programa de fidelidade — 3 pedidos por mês.',
            ],
            [
                'nome' => 'Juliana Costa',
                'cpf' => '123.456.789-09',
                'email' => 'juliana.costa@email.com',
                'telefone' => '(19) 96666-3030',
                'endereco' => 'Rua da Basílica, 27 — Vila Nova',
                'cidade' => 'Jundiaí',
                'uf' => 'SP',
                'observacoes' => 'Restrição alimentar: sem lactose.',
            ],
        ];

        foreach ($clientes as $dados) {
            Cliente::updateOrCreate(
                ['cpf_hash' => Cliente::hashCpf($dados['cpf'])],
                $dados,
            );
        }

        $pizzas = [
            [
                'sabor' => 'Margherita Artesanal',
                'ingredientes' => 'Molho de tomate San Marzano, mozzarella de búfala, manjericão fresco e azeite extravirgem.',
                'tamanho' => 'M',
                'preco' => 54.90,
                'disponivel' => true,
                'personalizada' => false,
            ],
            [
                'sabor' => 'Calabresa Defumada',
                'ingredientes' => 'Molho artesanal, mozzarella, calabresa defumada fatiada, cebola roxa e orégano.',
                'tamanho' => 'G',
                'preco' => 69.90,
                'disponivel' => true,
                'personalizada' => false,
            ],
            [
                'sabor' => 'Quatro Queijos',
                'ingredientes' => 'Mozzarella, gorgonzola, parmesão e catupiry original sobre massa de fermentação natural.',
                'tamanho' => 'M',
                'preco' => 64.90,
                'disponivel' => true,
                'personalizada' => false,
            ],
            [
                'sabor' => 'Vegana da Casa',
                'ingredientes' => 'Queijo vegetal, tomate confit, rúcula, cogumelos e pesto de castanha (item personalizado).',
                'tamanho' => 'P',
                'preco' => 49.90,
                'disponivel' => true,
                'personalizada' => true,
            ],
        ];

        foreach ($pizzas as $dados) {
            Pizza::updateOrCreate(
                ['sabor' => $dados['sabor'], 'tamanho' => $dados['tamanho']],
                $dados,
            );
        }

        $pedidos = [
            [
                'cliente' => 'Mariana Oliveira',
                'pizza' => 'Margherita Artesanal',
                'quantidade' => 2,
                'status' => 'entregue',
                'data_pedido' => '2024-08-15',
                'data_entrega' => '2024-08-15',
                'observacoes' => 'Entregar após as 19h.',
            ],
            [
                'cliente' => 'Rafael Pereira',
                'pizza' => 'Calabresa Defumada',
                'quantidade' => 1,
                'status' => 'entregue',
                'data_pedido' => '2025-03-22',
                'data_entrega' => '2025-03-22',
                'observacoes' => 'Pagamento no programa de fidelidade.',
            ],
            [
                'cliente' => 'Juliana Costa',
                'pizza' => 'Vegana da Casa',
                'quantidade' => 1,
                'status' => 'em_preparo',
                'data_pedido' => '2026-10-28',
                'data_entrega' => '2026-10-29',
                'observacoes' => 'Sem lactose — confirmar queijo vegetal.',
            ],
            [
                'cliente' => 'Mariana Oliveira',
                'pizza' => 'Quatro Queijos',
                'quantidade' => 3,
                'status' => 'pendente',
                'data_pedido' => '2026-10-29',
                'data_entrega' => '2026-10-30',
                'observacoes' => 'Agendado para comemoração em família.',
            ],
            [
                'cliente' => 'Rafael Pereira',
                'pizza' => 'Margherita Artesanal',
                'quantidade' => 2,
                'status' => 'entregue',
                'data_pedido' => '2025-11-10',
                'data_entrega' => '2025-11-10',
                'observacoes' => null,
            ],
        ];

        foreach ($pedidos as $dados) {
            $cliente = Cliente::where('nome', $dados['cliente'])->firstOrFail();
            $pizza = Pizza::where('sabor', $dados['pizza'])->firstOrFail();

            Pedido::updateOrCreate(
                [
                    'cliente_id' => $cliente->id,
                    'pizza_id' => $pizza->id,
                    'data_pedido' => $dados['data_pedido'],
                ],
                [
                    'quantidade' => $dados['quantidade'],
                    'status' => $dados['status'],
                    'data_entrega' => $dados['data_entrega'],
                    'valor_total' => round((float) $pizza->preco * $dados['quantidade'], 2),
                    'observacoes' => $dados['observacoes'],
                ],
            );
        }
    }
}
