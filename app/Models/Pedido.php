<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'pizza_id',
        'quantidade',
        'status',
        'data_pedido',
        'data_entrega',
        'valor_total',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'data_pedido' => 'date',
            'data_entrega' => 'date',
            'valor_total' => 'decimal:2',
        ];
    }

    public const STATUS = [
        'pendente' => 'Pendente',
        'em_preparo' => 'Em preparo',
        'saiu_entrega' => 'Saiu para entrega',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pizza(): BelongsTo
    {
        return $this->belongsTo(Pizza::class);
    }

    public function getStatusDescricaoAttribute(): string
    {
        return self::STATUS[$this->status] ?? ucfirst($this->status);
    }

    public function getAnoAttribute(): int
    {
        return (int) $this->data_pedido?->format('Y');
    }

    /**
     * Ordena os pedidos pelo ano da data do pedido (mais recente primeiro),
     * depois pela data completa.
     */
    public function scopeOrdenadoPorAno(Builder $query): Builder
    {
        $ano = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y', data_pedido)"
            : 'YEAR(data_pedido)';

        return $query->orderByRaw("{$ano} DESC")->orderByDesc('data_pedido')->orderByDesc('id');
    }
}
