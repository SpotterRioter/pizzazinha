<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'sabor',
        'ingredientes',
        'tamanho',
        'preco',
        'disponivel',
        'personalizada',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'disponivel' => 'boolean',
            'personalizada' => 'boolean',
        ];
    }

    public const TAMANHOS = [
        'P' => 'Pequena (P)',
        'M' => 'Média (M)',
        'G' => 'Grande (G)',
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function getTamanhoDescricaoAttribute(): string
    {
        return self::TAMANHOS[$this->tamanho] ?? $this->tamanho;
    }
}
